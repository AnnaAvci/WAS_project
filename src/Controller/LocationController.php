<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Location;
use App\Entity\PostLike;
use App\Form\LocationType;
use App\Entity\LocationBook;
use App\Entity\PhotoLocation;
use App\Form\LocationBookType;
use App\Entity\CommentUserLocation;
use App\Form\CommentUserLocationType;
use App\Repository\LocationRepository;
use App\Repository\PostLikeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationController extends AbstractController
{
    /**
     * @Route("/location", name="app_location")
     */
    public function index(ManagerRegistry $doctrine, LocationRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $locations = $paginatorInterface->paginate(
            $repo->findAllWithPagination(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        return $this->render('location/index.html.twig', [
            'locations' => $locations,

        ]);
    }




    /**
     * @Route("/host/location/add", name="add_location")
     * @Route("/host/location/update/{id}", name = "update_location")
     */
    public function add(ManagerRegistry $doctrine, Location $location = null, Request $request): Response
    {

        if ($this->getUser() && $this->isGranted("ROLE_HOST")) {
            $entityManager = $doctrine->getManager();
            if (!$location) {
                $location = new Location();
            }


            $entityManager = $doctrine->getManager();
            $form = $this->createForm(LocationType::class, $location);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                // getting uploaded photos
                $photoLocations = $form->get('photoLocation')->getData();
                // As there can be multiple photos, we're making a foreach
                foreach ($photoLocations as $photoLocation) {
                    // generating a unique name for each photo to avoid mix-ups
                    $file = md5(uniqid()) . '.' . $photoLocation->guessExtension();
                    //copying the photos to the uploads folder; first we put the destination, then the file
                    $photoLocation->move(
                        $this->getParameter('images_directory'),
                        $file
                    );
                    // storing the photos' names in the database
                    $img = new PhotoLocation;
                    $img->setNamePhoto($file);
                    $location->addPhotoLocation($img);
                }


                // "hydration"
                $location = $form->getData();
                $location->setOwner($this->getUser());
                // prepares data for being inserted into the database
                $entityManager->persist($location);
                // inserts data in database
                $entityManager->flush();

                return $this->redirectToRoute('show_location', ["id" => $location->getId()]);
            }



            return $this->render('location/add.html.twig', [
                'formLocation' => $form->createView(),
                'location' => $location,
                "isUpdate" => $location->getId() !== null

            ]);
        }
    }

    /**
     * @Route("/host/delete/photoLocation/{id}", name="location_delete_photoLocation")
     */
    public function deletePhoto(ManagerRegistry $doctrine, PhotoLocation $photoLocation, Request $request)
    {

        $name = $photoLocation->getNamePhoto();
        unlink($this->getParameter('images_directory') . '/' . $name);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($photoLocation);
        $entityManager->flush();

        return $this->redirectToRoute('show_location', ["id" => $photoLocation->getLocation()->getId()]);
    }



    /**
     * @Route("location/delete/{id}", name = "delete_location")
     * 
     */
    public function delete(ManagerRegistry $doctrine, Location $location)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($location);
        $entityManager->flush();
        return $this->redirectToRoute('app_location');
    }




    /**
     * @Route("/location/{id}", name="show_location")
     */
    public function show(Location $location, ManagerRegistry $doctrine, CommentUserLocation $commentUserLocation = null, LocationBook $locationBook = null, Request $request): Response
    {
        //adding comments to a location from a dedicated form type
        // Classes declared as null will get their data from a form that will be on that page
        $entityManager = $doctrine->getManager();
        $commentUserLocation = new CommentUserLocation();
        $form = $this->createForm(CommentUserLocationType::class, $commentUserLocation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // "hydration"
            $commentUserLocation = $form->getData();
            $commentUserLocation->setCommenter($this->getUser());
            $commentUserLocation->setLocation($location);
            $commentUserLocation->setDateComment(new \DateTime());
            /* $Location->setCommentUserLocation($commentUserLocation); */
            $entityManager->persist($commentUserLocation);
            // inserts data in database
            $entityManager->flush();

            return $this->redirectToRoute('show_location', ["id" => $location->getId()]);
        }


        //booking a location 
        $entityManager = $doctrine->getManager();
        $locationBook = new LocationBook();
        // 
        $form1 = $this->createForm(locationBookType::class, $locationBook);
        $form1->handleRequest($request);


        if ($form1->isSubmitted() && $form1->isValid()) {

            // any data that is not taken from form
            $locationBook = $form1->getData();
            $locationBook->setLocationClient($this->getUser());
            $locationBook->setLocation($location);
            $locationBook->setDateCreated(new \DateTime());
            $locationBook->isIsAccepted(0);

            $entityManager->persist($locationBook);
            // inserts data in database
            $entityManager->flush();

            return $this->redirectToRoute('show_location', ["id" => $location->getId()]);
        }

        // view for individual location
        return $this->render('Location/show.html.twig', [
            'location' => $location,
            'formCommentUserLocation' => $form->createView(),
            'formLocationBook' => $form1->createView()
        ]);
    }


    /**
     * Allows to like/unlike a location
     * @Route("/location/{id}/like", name="location_like")
     * @param Location $location
     * @param ManagerRegistry $doctrine
     * @param PostLikeRepository $repo
     * @return Response
     */
    public function like(Location $location, ManagerRegistry $doctrine, PostLikeRepository $repo): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        // if user is not connected, error 403
        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Please log in to like a post"
            ], 403);
        }

        // unlike location if already liked and count new nb of likes, success code 200 = an http status
        if ($location->isLikedByUser($user)) {
            $like = $repo->findOneBy([
                'location' => $location,
                'user' => $user
            ]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json([
                'code' => 200,
                'message' => "You no longer like this location",
                'likes' => $repo->count(['location' => $location])

            ], 200);
        }

        $like = new PostLike();
        $like->setLocation($location)
            ->setUser($user);
        $entityManager->persist($like);
        $entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => "You like this location",
            'likes' => $repo->count(['location' => $location])

        ], 200);
    }

 

}
