<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Location;
use App\Entity\BookService;
use App\Entity\BookLocation;
use App\Entity\PhotoService;
use App\Entity\PhotoLocation;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }


     /**
     * @Route("/user/declineBookLocation/{id}", name="decline_book_location")
     * 
     */
    public function declineBookLocation(ManagerRegistry $doctrine, BookLocation $bookLocation, User $user)
    {
    
        $entityManager = $doctrine->getManager();
        $bookLocation->setIsAccepted(2);
        $entityManager->flush();
        return $this->redirectToRoute('show_user',["id"=>$user->getId()]);
    }
 

     /**
     * @Route("/user/declineBookService/{id}", name="decline_book_service")
     * 
     */
    public function declineBookService(ManagerRegistry $doctrine, BookService $bookService, User $user)
    {
    
        $entityManager = $doctrine->getManager();
        $bookService->setIsAccepted(2);
        $entityManager->flush();
        return $this->redirectToRoute('show_user',["id"=>$user->getId()]);
    }


    /**
     * @Route("/user/acceptBookLocation/{id}", name="accept_book_location")
     * 
     */
    public function acceptBookLocation(ManagerRegistry $doctrine, BookLocation $bookLocation)
    {
    
        $entityManager = $doctrine->getManager();
        $bookLocation->setIsAccepted(1);
        $entityManager->flush();
        return $this->redirectToRoute('show_user' ,["id"=>$bookLocation->getLocation()->getOwnerLocation()->getId()] );
     
    }

    /**
     * @Route("/user/acceptBookService/{id}", name="accept_book_service")
     * 
     */
    public function acceptBookService(ManagerRegistry $doctrine, BookService $bookService)
    {
    
        $entityManager = $doctrine->getManager();
        $bookService->setIsAccepted(1);
        $entityManager->flush();
        return $this->redirectToRoute('show_user' ,["id"=>$bookService->getService()->getProviderService()->getId()] );
     
    }

    /**
     * @Route("/user/{id}", name="show_user")
     */
    public function show(ManagerRegistry $doctrine, User $user):Response
    {
        $location = $doctrine->getRepository(Location::class)->findBy(["owner_location" => $this->getUser()]);
        $service = $doctrine->getRepository(Service::class)->findBy(["provider_service" => $this->getUser()]);
        return $this->render ('user/show.html.twig', [
            'user' => $user,
            'location'=>$location,
            'service'=>$service,
        ]);
    }



    /**
     * @Route("/user/edit/{id}", name="edit_user")
     */
    public function editAccount(Request $request, ManagerRegistry $doctrine, User $user = null): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

         if ($this->getUser()->getId() === $user->getId()) {

            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user = $form->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Profile information updated');

                return $this->redirectToRoute('show_user',["id"=>$user->getId()]);
            }

            return $this->render('user/edit.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        } 
    }


}
