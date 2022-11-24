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

    /**
     * @Route("/user/declineLocationBook/{id}", name="decline_location_book")
     * 
     */
    public function declineLocationBook(ManagerRegistry $doctrine, LocationBook $locationBook, User $user)
    {

        $entityManager = $doctrine->getManager();
        $locationBook->setIsAccepted(2);
        $entityManager->flush();
        return $this->redirectToRoute('show_user', ["id" => $user->getId()]);
    }


    /**
     * @Route("/user/acceptLocationBook/{id}", name="accept_location_book")
     * 
     */
    public function acceptLocationBook(ManagerRegistry $doctrine, LocationBook $locationBook)
    {

        $entityManager = $doctrine->getManager();
        $locationBook->setIsAccepted(1);
        $entityManager->flush();
        return $this->redirectToRoute('show_user', ["id" => $locationBook->getLocation()->getOwner()->getId()]);
    }



}
