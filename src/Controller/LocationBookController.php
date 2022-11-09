<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Location;
use App\Entity\ServiceBook;
use App\Entity\LocationBook;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationBookController extends AbstractController
{
    /**
     * @Route("/book/{id}", name="app_book")
     */
    public function index(ManagerRegistry $doctrine, User $user): Response
    {
        $location = $doctrine->getRepository(Location::class)->findBy(["owner" => $this->getUser()]);
        $service = $doctrine->getRepository(Service::class)->findBy(["owner" => $this->getUser()]);
        $serviceBook = $doctrine->getRepository(ServiceBook::class)->findAll();
        $locationBook = $doctrine->getRepository(LocationBook::class)->findAll();

        return $this->render('location_book/index.html.twig', [
            'user' => $user,
            'location' => $location,
            'locationBook' => $locationBook,
            'service' => $service,
            'serviceBook' => $serviceBook,
        ]);
    }




}
