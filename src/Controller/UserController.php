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

            // Anonymise Comments
            $locationComments= $user->getCommentUserLocations();
            foreach($locationComments as $comment){
                $comment->setCommenter(null);
            }
            $serviceComments= $user->getCommentUserServices();
            foreach($serviceComments as $comment){
                $comment->setCommenter(null);
            }

            // Anonymise sent messages
            $sentMsgs = $user->getSent();
            foreach ($sentMsgs as $sentMsg) {
                $sentMsg->setSender(null);
            }


            // Anonymise reservations
            $locationBooks = $user->getLocationBooks();
            foreach ($locationBooks as $locationBook) {
                $locationBook->setLocationClient(null);
            }
            $serviceBooks = $user->getServiceBooks();
            foreach ($serviceBooks as $serviceBook) {
                $serviceBook->setServiceClient(null);
            }


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

            $entityManager = $doctrine->getManager();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // getting uploaded photos
                $pictureUser = $form->get('picture_user')->getData();
    

                if ($pictureUser) {
                    // generating a unique name for each photo to avoid mix-ups
                    $file = md5(uniqid()) . '.' . $pictureUser->guessExtension();
                    //copying the photos to the uploads folder; first we put the destination, then the file
                    $pictureUser->move(
                        $this->getParameter('images_directory'),
                        $file
                    );

                    //$nom = $user->getPictureUser();
                    //  On supprime le fichier
                    unlink($this->getParameter('images_directory').'/'.$file);
                    //  On crée on stock dans la bdd son nom et l'img stocké dans le disque 
                    $user->setPictureUser($file);

                    //$user = $form ->getData();
                    $entityManager->persist($user);
                    // $entityManager->flush();
                } else {
                    //$user = $form->getData();
                    $entityManager->persist($user);
                    // $entityManager->flush();
                }

                // $user = $form->getData();
                // $entityManager = $doctrine->getManager();
                // $entityManager->persist($user);
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
