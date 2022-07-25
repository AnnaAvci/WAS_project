<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
