<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\PostLike;
use App\Form\ServiceType;
use App\Entity\ServiceBook;
use App\Entity\PhotoService;
use App\Form\ServiceBookType;
use App\Entity\CommentUserService;
use App\Form\CommentUserServiceType;
use App\Repository\ServiceRepository;
use App\Repository\PostLikeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ServiceController extends AbstractController
{
    /**
     * @Route("/photoshoots", name="app_service")
     */
    public function index(ManagerRegistry $doctrine, ServiceRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $services = $paginatorInterface->paginate(
            $repo->findAllWithPagination(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );    
        return $this->render('service/index.html.twig', [
                'services' => $services,
            
            ]);
    }


    /**
     * @Route("/photographer/photoshoots/add", name="add_service")
     * @Route("/photographer/photoshoots/update/{id}", name = "update_service")
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
        $service->setOwner($this->getUser()) ;
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
        return $this->redirectToRoute('show_service',["id"=>$photoService->getService()->getId()]);

    }




    /**
     * @Route("/photoshoot/{id}", name="show_service")
     * 
     */
        public function show(Service $service, ManagerRegistry $doctrine, CommentUserService $commentUserService = null, ServiceBook $serviceBook = null, Request $request):Response
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
              $serviceBook = new ServiceBook(); 

              $form1 = $this->createForm(ServiceBookType::class, $serviceBook);
              $form1->handleRequest($request);
  
          
              if($form1->isSubmitted() && $form1->isValid()){

            $serviceBook = $form1->getData();
            $serviceBook->setServiceClient($this->getUser()); ;
            $serviceBook->setService($service);
            $serviceBook->setDateCreated(new \DateTime());
            $serviceBook->isIsAccepted(0); 
                
                  $entityManager->persist($serviceBook);
                  // inserts data in database
                  $entityManager->flush();
      
                  return $this->redirectToRoute('show_service',["id"=>$service->getId()]);
              }

    

            return $this->render ('service/show.html.twig', [
                'service' => $service,
                'formCommentUserService' => $form->createView(),
                'formServiceBook' => $form1->createView()
            ]);

        }



    /**
     * @Route("photoshoot/delete/{id}", name = "delete_service")
     * 
     */
    public function delete(ManagerRegistry $doctrine, Service $service){
        $entityManager = $doctrine->getManager();
        $entityManager->remove($service);
        $entityManager->flush();
        return $this->redirectToRoute('app_service');
    }




}