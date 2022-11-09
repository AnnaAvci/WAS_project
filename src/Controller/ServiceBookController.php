<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceBookController extends AbstractController
{
    /**
     * @Route("/book/service", name="app_service_book")
     */
    public function index(): Response
    {
        return $this->render('service_book/index.html.twig', [
            'controller_name' => 'ServiceBookController',
        ]);
    }
}
