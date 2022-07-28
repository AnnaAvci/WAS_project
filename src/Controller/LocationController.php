<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Location;
use App\Form\LocationType;
use App\Entity\BookLocation;
use App\Entity\PhotoLocation;
use App\Form\BookLocationType;
use App\Entity\CommentUserLocation;
use App\Form\CommentUserLocationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class LocationController extends AbstractController
{
        /**
         * @Route("/location", name="app_location")
         */
        public function index(ManagerRegistry $doctrine): Response
        {
            $locations = $doctrine->getRepository(Location::class)->findAll();

            return $this->render('location/index.html.twig', [
                'locations' => $locations,
            
            ]);
        
        }

  


    /**
     * @Route("/host/location/add", name="add_location")
     * @Route("/host/location/update/{id}", name = "update_location")
     */
    public function add(ManagerRegistry $doctrine, Location $location = null, Request $request):Response
    {
        
        if($this->getUser() && $this->isGranted("ROLE_HOST"))
        {
            $entityManager = $doctrine->getManager();
             if(!$location) {
                 $location = new Location();
             }
    
    
            $entityManager = $doctrine->getManager();   
            $form = $this->createForm(LocationType::class, $location);
            $form->handleRequest($request);
            
    
            if($form->isSubmitted() && $form->isValid()){
                // getting uploaded photos
                $photoLocations = $form->get('photoLocation')->getData();
                // As there can be multiple photos, we're making a foreach
                foreach($photoLocations as $photoLocation){
                    // generating a unique name for each photo to avoid mix-ups
                    $file = md5(uniqid()).'.'.$photoLocation->guessExtension();
                    //copying the photos to the uploads folder; first we put the destination, then the file
                    $photoLocation->move(
                        $this->getParameter('img_directory'),
                        $file
                    );
                    // storing the photos' names in the database
                    $img = new PhotoLocation;
                    $img->setNamePhoto($file);
                    $location->addPhotoLocation($img);
                }
    
    
                // "hydration"
                $location = $form->getData();
                $location->setOwnerLocation($this->getUser()) ;
                // prepares data for being inserted into the database
                $entityManager->persist($location);
                // inserts data in database
                $entityManager->flush();
    
                return $this->redirectToRoute('show_location',["id"=>$location->getId()]);
            } 
          
    
    
            return $this->render ('location/add.html.twig', [
                'formLocation' => $form->createView(),
                'location' => $location
         
            ]);

        }


    }

    /**
     * @Route("/host/delete/photoLocation/{id}", name="location_delete_photoLocation")
     */
    public function deletePhoto (ManagerRegistry $doctrine, PhotoLocation $photoLocation, Request $request ){
        
            $name = $photoLocation->getNamePhoto();
            unlink($this->getParameter('images_directory').'/'.$name);
            $entityManager = $doctrine->getManager();
            $entityManager->remove($photoLocation);
            $entityManager->flush();

            return $this->redirectToRoute('show_location',["id"=>$photoLocation->getLocation()->getId()]);

        }
    


    /**
     * @Route("location/delete/{id}", name = "delete_location")
     * 
     */
    public function delete(ManagerRegistry $doctrine, Location $location){
        $entityManager = $doctrine->getManager();
        $entityManager->remove($location);
        $entityManager->flush();
        return $this->redirectToRoute('app_location');
    }


    
        /**
         * @Route("/location/{id}", name="show_location")
         */
        public function show(Location $location, ManagerRegistry $doctrine, CommentUserLocation $commentUserLocation = null , BookLocation $bookLocation = null, Request $request):Response
        {
            //adding comments to a location from a dedicated form type
            // Classes declared as null will get their data from a form that will be on that page
            $entityManager = $doctrine->getManager();  
            $commentUserLocation = new CommentUserLocation(); 
            $form = $this->createForm(CommentUserLocationType::class, $commentUserLocation);
            $form->handleRequest($request);
            
          
            if($form->isSubmitted() && $form->isValid()){
                // "hydration"
                $commentUserLocation = $form->getData();
                $commentUserLocation->setCommenter($this->getUser()) ; 
                $commentUserLocation->setLocation($location);
                $commentUserLocation->setDateComment(new \DateTime());
                /* $Location->setCommentUserLocation($commentUserLocation); */
                $entityManager->persist($commentUserLocation);
                // inserts data in database
                $entityManager->flush();
    
                return $this->redirectToRoute('show_location',["id"=>$location->getId()]);
            }
    

            //booking a location 
            $entityManager = $doctrine->getManager();  
            $bookLocation = new BookLocation(); 
            // 
            $form1 = $this->createForm(BookLocationType::class, $bookLocation);
            $form1->handleRequest($request);

        
            if($form1->isSubmitted() && $form1->isValid()){

                // any data that is not taken from form
                $bookLocation = $form1->getData();
                $bookLocation->setLocationClient($this->getUser()); 
                $bookLocation->setLocation($location);
                $bookLocation->setDateCreated(new \DateTime());
                $bookLocation->isIsAccepted(0); 
              
                $entityManager->persist($bookLocation);
                // inserts data in database
                $entityManager->flush();
    
                return $this->redirectToRoute('show_location',["id"=>$location->getId()]);
            }
    



            // view for individual location
            return $this->render ('Location/show.html.twig', [
                'location' => $location,
                'formCommentUserLocation' => $form->createView(),
                'formBookLocation' => $form1->createView()
            ]);


        }



}