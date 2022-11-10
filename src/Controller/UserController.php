<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Location;
use App\Entity\ServiceBook;
use App\Entity\LocationBook;
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
     * @Route("/user/declineLocationBook/{id}", name="decline_location_book")
     * 
     */
    public function declineLocationBook(ManagerRegistry $doctrine, LocationBook $locationBook, User $user)
    {
    
        $entityManager = $doctrine->getManager();
        $locationBook->setIsAccepted(2);
        $entityManager->flush();
        return $this->redirectToRoute('show_user',["id"=>$user->getId()]);
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
        return $this->redirectToRoute('show_user',["id"=>$user->getId()]);
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
        return $this->redirectToRoute('show_user' ,["id"=> $locationBook->getLocation()->getOwner()->getId()] );
     
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
        return $this->redirectToRoute('show_user' ,["id"=> $serviceBook->getService()->getOwner()->getId()] );
     
    }

    /**
     * @Route("/user/{id}", name="show_user")
     */
    public function show(ManagerRegistry $doctrine, User $user):Response
    {
        $location = $doctrine->getRepository(Location::class)->findBy(["owner" => $this->getUser()]);
        $service = $doctrine->getRepository(Service::class)->findBy(["owner" => $this->getUser()]);
        return $this->render ('user/show.html.twig', [
            'user' => $user,
            'location'=>$location,
            'service'=>$service,
        ]);
    }


    /**
     * @Route("/account/delete/{id}", name="delete_user")
     */
    public function deleteUserAccount(ManagerRegistry $doctrine, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // deny the access if the user is not completely authenticated

        if ($this->getUser() === $user) {
            // set token to null or it will throw an error 
            //$user = $this->getUser();
            $this->container->get('security.token_storage')->setToken(null);

            $entityManager = $doctrine->getManager();
          /*   $locationComments= $user->getCommentUserLocations();
            
            foreach($locationComments as $comment){
                $comment->setCommenter('');
            } */
            $entityManager->remove($user);
            $entityManager->flush();

          

            return $this->redirectToRoute('app_home');
        } else {
            return $this->redirectToRoute('app_home');
        }

      
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
                'user' => $user,
                'registrationForm' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        } 
    }


}
