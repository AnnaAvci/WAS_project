<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ServiceBook;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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


    /**
     * @Route("/user/declineServiceBook/{id}", name="decline_service_book")
     * 
     */
    public function declineServiceBook(ManagerRegistry $doctrine, ServiceBook $serviceBook, User $user)
    {

        $entityManager = $doctrine->getManager();
        $serviceBook->setIsAccepted(2);
        $entityManager->flush();
        return $this->redirectToRoute('show_user', ["id" => $user->getId()]);
    }

    /**
     * @Route("/user/acceptServiceBook/{id}", name="accept_service_book")
     * 
     */
    public function acceptServiceBook(ManagerRegistry $doctrine, ServiceBook $serviceBook)
    {

        $entityManager = $doctrine->getManager();
        $serviceBook->setIsAccepted(1);
        $entityManager->flush();
        return $this->redirectToRoute('show_user', ["id" => $serviceBook->getService()->getOwner()->getId()]);
    }
}
