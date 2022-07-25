<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookLocationController extends AbstractController
{
    /**
     * @Route("/book/location", name="app_book_location")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        return $this->render('book_location/index.html.twig', [
            'controller_name' => 'BookLocationController',
        ]);
    }
}
