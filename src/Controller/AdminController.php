<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Location;
use App\Form\ServiceType;
use App\Form\LocationType;
use App\Entity\PhotoService;
use App\Entity\PhotoLocation;
use App\Repository\UserRepository;
use App\Repository\ServiceRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin-dashboard", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
      
        ]);
    }

    /**
     * @Route("/admin/locations", name="admin_locations")
     */
    public function adminLocations(LocationRepository $locationRepo): Response
    {
        $locations = $locationRepo->findAll();
        return $this->render('admin/adminLocations.html.twig', [
            "locations" => $locations,
        ]);
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function adminUsers(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();
        return $this->render('admin/adminUsers.html.twig', [
            "users" => $users,
        ]);
    }

    /**
     * @Route("/admin/photoshoots", name="admin_services")
     */
    public function adminServices(ServiceRepository $serviceRepo): Response
    {
        $services = $serviceRepo->findAll();
        return $this->render('admin/adminServices.html.twig', [
            "services" => $services,
        ]);
    }



    /**
     * @Route("/admin/location/delete/{id}", name="admin_delete_location")
     */
    public function suppression(Location $location, ManagerRegistry $doctrine, Request $request)
    {
        /*  if ($this->isCsrfTokenValid("SUP" . $location->getId(), $request->get('_token'))) { */
        $entityManager = $doctrine->getManager();
        $entityManager->remove($location);
        $entityManager->flush();
        $this->addFlash("success", "The location has been deleted");
        return $this->redirectToRoute('admin_locations');
        
    }



    /**
     * @Route("admin/account/delete/{id}", name="admin_delete_user")
     */
    public function deleteUserAccount(ManagerRegistry $doctrine, User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
  
            $this->container->get('security.token_storage')->setToken(null);
            $this->addFlash("danger", "You are about to delete the user");
            $entityManager = $doctrine->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
       
            return $this->redirectToRoute('admin_users');
    }






    /**
     * @Route("admin/photoshoot/delete/{id}", name = "admin_delete_service")
     * 
     */
    public function delete(ManagerRegistry $doctrine, Service $service)
    {
        $this->addFlash("danger", "You are about to delete the photoshoot");
        $entityManager = $doctrine->getManager();
        $entityManager->remove($service);
        $entityManager->flush();
        return $this->redirectToRoute('admin_services');
    }


    /**
     * @Route("/admin/photoshoots/update/{id}", name = "admin_update_service")
     */
    public function modifyService(ManagerRegistry $doctrine, Service $service = null, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // getting uploaded photos
            $photoServices = $form->get('photoService')->getData();
            // As there can be multiple photos, we're making a foreach
            foreach ($photoServices as $photoService) {
                // generating a unique name for each photo to avoid mix-ups
                $file = md5(uniqid()) . '.' . $photoService->guessExtension();
                //copying the photos to the uploads folder; first we put the destination, then the file
                $photoService->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                // storing the photos' names in the database
                $img = new PhotoService;
                $img->setNamePhoto($file);
                $service->addPhotoService($img);
            }

            // "hydration"
            $service = $form->getData();
            $service->setOwner($this->getUser());
            $entityManager->persist($service);
            // inserts data in database
            $entityManager->flush();
        
            return $this->redirectToRoute('show_service', ["id" => $service->getId()]);
            $this->addFlash("success", "Photoshoot has been updated");
        }

        return $this->render('admin/modif-service.html.twig', [
            'formService' => $form->createView(),
            'service' => $service
        ]);
    }

    /**
     * @Route("/admin/location/update/{id}", name = "admin_update_location")
     */
    public function modifyLocation(ManagerRegistry $doctrine, Location $location = null, Request $request): Response
    {
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
            $entityManager->persist($location);
            // inserts data in database
            $entityManager->flush();
            $this->addFlash("success", "Location has been updated");
            return $this->redirectToRoute('show_location', ["id" => $location->getId()]);
        }

        return $this->render('admin/modif-location.html.twig', [
            'formLocation' => $form->createView(),
            'location' => $location
        ]);
    }



}