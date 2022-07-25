<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Entity\BookService;
use App\Form\BookServiceType;
use App\Entity\CommentUserService;
use App\Form\CommentUserServiceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\PhotoService;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="app_service")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $services = $doctrine->getRepository(Service::class)->findAll();

            return $this->render('service/index.html.twig', [
                'services' => $services,
            
            ]);
    }


    /**
     * @Route("/photographer/service/add", name="add_service")
     * @Route("/photographer/service/update/{id}", name = "update_service")
     */
    public function add(ManagerRegistry $doctrine, service $service = null, Request $request):Response
    {
        if(!$service){
            $service = new service();
        }


        $entityManager = $doctrine->getManager();    
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){

        // getting uploaded photos
        $photoServices = $form->get('photoService')->getData();
        // As there can be multiple photos, we're making a foreach
        foreach($photoServices as $photoService){
            // generating a unique name for each photo to avoid mix-ups
            $file = md5(uniqid()).'.'.$photoService->guessExtension();
            //copying the photos to the uploads folder; first we put the destination, then the file
            $photoService->move(
                $this->getParameter('img_directory'),
                $file
            );
            // storing the photos' names in the database
            $img = new PhotoService;
            $img->setNamePhoto($file);
            $service->addPhotoService($img);
        }

        // "hydration"
        $service = $form->getData();
        $service->setProviderService($this->getUser()) ;
        $entityManager->persist($service);
        // inserts data in database
        $entityManager->flush();

        return $this->redirectToRoute('show_service',["id"=>$service->getId()]);
        }

        return $this->render ('service/add.html.twig', [
            'formService' => $form->createView(),
            'service' => $service
        ]);

    }



    /**
     * @Route("/photographer/delete/photoService/{id}", name="service_delete_photoService")
     */
    public function deletePhoto (ManagerRegistry $doctrine, PhotoService $photoService, Request $request ){
        
           
        $entityManager = $doctrine->getManager();
        $entityManager->remove($photoService);
        $entityManager->flush();
        return $this->redirectToRoute('show_location',["id"=>$photoService->getService()->getId()]);

    }




         /**
         * @Route("/service/{id}", name="show_service")
         * 
         */
        public function show(Service $service, ManagerRegistry $doctrine, CommentUserService $commentUserService = null, BookService $bookService = null, Request $request):Response
        {
            // adding comments from users
            $entityManager = $doctrine->getManager(); 
            $commentUserService = new CommentUserService();    
            $form = $this->createForm(CommentUserServiceType::class, $commentUserService);
            $form->handleRequest($request);
            
    
            if($form->isSubmitted() && $form->isValid()){
                // "hydration"
                $commentUserService = $form->getData();
                $commentUserService->setCommenter($this->getUser()) ; 
                $commentUserService->setService($service);
                $commentUserService->setDateComment(new \DateTime());
              
                $entityManager->persist($commentUserService);
                // inserts data in database
                $entityManager->flush();
    
                return $this->redirectToRoute('show_service',["id"=>$service->getId()]);
            }



              //booking a service
              $entityManager = $doctrine->getManager(); 
              $bookService = new BookService(); 

              $form1 = $this->createForm(BookServiceType::class, $bookService, ['id' => $service->getId()]);
              $form1->handleRequest($request);
  
          
              if($form1->isSubmitted() && $form1->isValid()){
  
                  $bookService = $form1->getData();
                  $bookService->setServiceClient($this->getUser()); ; 
                  $bookService->setService($service);
                  $bookService->setDateCreated(new \DateTime());
                  $bookService->isAccepted(0); 
                
                  $entityManager->persist($bookService);
                  // inserts data in database
                  $entityManager->flush();
      
                  return $this->redirectToRoute('show_service',["id"=>$service->getId()]);
              }

    

            return $this->render ('service/show.html.twig', [
                'service' => $service,
                'formCommentUserService' => $form->createView(),
                'formBookService' => $form1->createView()
            ]);

        }


        
    /**
     * @Route("service/delete/{id}", name = "delete_service")
     * 
     */
    public function delete(ManagerRegistry $doctrine, Service $service){
        $entityManager = $doctrine->getManager();
        $entityManager->remove($service);
        $entityManager->flush();
        return $this->redirectToRoute('app_service');
    }
}