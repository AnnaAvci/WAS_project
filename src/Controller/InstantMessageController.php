<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Location;
use App\Form\MessageType;
use App\Entity\InstantMessage;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\InstantMessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class InstantMessageController extends AbstractController
{
    /**
     * @Route("/messages", name="app_messages")
     */
    public function index(): Response
    {
        return $this->render('instant_message/index.html.twig', [
            'controller_name' => 'InstantMessageController',
        ]);
    }

    
    /**
     * @Route("/send", name="send")
     * 
     */
    public function send(ManagerRegistry $doctrine, InstantMessageRepository $repository, Request $request, Location $location, User $user): Response
    {
        // creating a new message with the help of the form
        $message = new InstantMessage;
        $messageForm = $this->createForm(MessageType::class, $message);
        
        // gets data from form, which is why we can check if it's submitted and valid
        $messageForm->handleRequest($request);

        if($messageForm->isSubmitted() && $messageForm->isValid()){

            // the sender is the current user
            $message->setSender($this->getUser());
            // $recipient = $doctrine->getRepository(InstantMessage::class)->findMessageRecipient();
            $recipient = $repository->findMessageRecipient($user, $location->getId());
            $message->setRecipient($recipient);
            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent");
            return $this->redirectToRoute("app_messages");
        }

        return $this->render("instant_message/send.html.twig", [
            "messageForm" => $messageForm->createView()
        ]);
    }


    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('instant_message/sent.html.twig');
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(ManagerRegistry $doctrine, InstantMessage $message): Response
    {
        $message->setIsRead(true);
        $em = $doctrine->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('instant_message/read.html.twig', compact("message"));
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(ManagerRegistry $doctrine, InstantMessage $message): Response
    {
        $em = $doctrine->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("app_messages");
    }
}
