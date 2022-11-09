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
     * @Route("comment/delete_comment/{id}", name = "delete_comment")
     * 
     */
    public function deleteComment(ManagerRegistry $doctrine, CommentUserLocation $commentUserLocation)
    {
        $location = $commentUserLocation->getLocation();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($commentUserLocation);
        $entityManager->flush();
        return $this->redirectToRoute('show_location', ["id" => $location->getId()]);
    }
}
