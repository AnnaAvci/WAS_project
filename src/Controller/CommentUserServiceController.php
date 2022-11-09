<?php

namespace App\Controller;

use App\Entity\CommentUserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentUserServiceController extends AbstractController
{
    /**
     * @Route("/comment/user/service", name="app_comment_user_service")
     */
    public function index(): Response
    {
        return $this->render('comment_user_service/index.html.twig', [
            'controller_name' => 'CommentUserServiceController',
        ]);
    }


    /**
     * @Route("comment/delete_comment/{id}", name = "delete_comment")
     * 
     */
    public function deleteComment(ManagerRegistry $doctrine, CommentUserService $commentUserService)
    {
        $service = $commentUserService->getService();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($commentUserService);
        $entityManager->flush();
        return $this->redirectToRoute('show_service', ["id" => $service->getId()]);
    }
}
