<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Location;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookLocationController extends AbstractController
{
    /**
     * @Route("/book/location", name="app_book")
     */
    public function index(ManagerRegistry $doctrine, User $user): Response
    {
        $bookLocation = $doctrine->getRepository(BookLocation::class)->findBy(["locationClient" => $this->getUser()]);
        $bookService = $doctrine->getRepository(BookService::class)->findBy(["serviceClient" => $this->getUser()]);
        return $this->render('book_location/index.html.twig', [
            'bookLocation' => $bookLocation,
            'bookService' => $bookService,
        ]);
    }
}
