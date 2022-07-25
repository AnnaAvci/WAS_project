<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookServiceController extends AbstractController
{
    /**
     * @Route("/book/service", name="app_book_service")
     */
    public function index(): Response
    {
        return $this->render('book_service/index.html.twig', [
            'controller_name' => 'BookServiceController',
        ]);
    }
}
