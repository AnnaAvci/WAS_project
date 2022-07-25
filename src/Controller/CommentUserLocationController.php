<?php

namespace App\Controller;

use App\Entity\CommentUserLocation;
use App\Form\CommentUserLocationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentUserLocationController extends AbstractController
{
    /**
     * @Route("/comment/user/location", name="app_comment_user_location")
     */
    public function index(): Response
    {
        return $this->render('comment_user_location/index.html.twig', [
            'controller_name' => 'CommentUserLocationController',
        ]);
    }


    /**
     * @Route("/location/show", name="add_comment_user_location")
     * 
     */
    /* public function add(ManagerRegistry $doctrine, CommentUserLocation $commentUserLocation = null, Request $request):Response
    {
        if(!$commentUserLocation){
            $commentUserLocation = new CommentUserLocation();
        }


        $entityManager = $doctrine->getManager();    
        $form = $this->createForm(CommentUserLocationType::class, $commentUserLocation);
        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){
            // "hydration"
            $commentUserLocation = $form->getData();
            $commentUserLocation->setCommenter($this->getUser());
          /*   $commentUserLocation->setLocation($this->getLocation()); */
         /*    $entityManager->persist($commentUserLocation);
            // inserts data in database
            $entityManager->flush();

            return $this->redirectToRoute('show_location');
        }

        return $this->render ('location/show.html.twig', [
            'formCommentUserLocation' => $form->createView()
        ]); */

// }
}
