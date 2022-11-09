<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Location;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message/{id}", name="app_message")
     */
    public function index(ManagerRegistry $doctrine, MessageRepository $repository, Request $request, User $user): Response
    {
        // creating a new message with the help of the form
        $message = new Message;
        $messageForm = $this->createForm(MessageType::class, $message);

        // gets data from form, which is why we can check if it's submitted and valid
        $messageForm->handleRequest($request);
        // $recipient = $location->getOwner();

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            // the sender is the current user
            $message->setSender($this->getUser());
            // $recipient = $repository->findMessageRecipient($user, $location->getId());

            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent");
            return $this->redirectToRoute("app_message", ["id" => $user->getId()]);
        }
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'user'=>$user,
            "messageForm" => $messageForm->createView()
        ]);
    }



    /**
     * @Route("/send", name="send")
     * 
     */
    public function send(ManagerRegistry $doctrine, MessageRepository $repository, Request $request, User $user): Response
    {
        // creating a new message with the help of the form
        $message = new Message;
        $messageForm = $this->createForm(MessageType::class, $message);

        // gets data from form, which is why we can check if it's submitted and valid
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $sender = $message->getSender();
            // the sender is the current user
            //$message->setSender($this->getUser());
            $message->setSender($sender);
            
            // $recipient = $repository->findMessageRecipient($user, $location->getId());
            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent");
            return $this->redirectToRoute("app_message",["id" => $user->getId()]);
        }

        return $this->render("message/read.html.twig", [
            "messageForm" => $messageForm->createView()
        ]);
    }




    /**
     * @Route("/send/{id}", name="send-message")
     * 
     */
    public function messageToLocation (ManagerRegistry $doctrine, MessageRepository $repository, Request $request, Location $location): Response
    {
        // creating a new message with the help of the form
        $message = new Message;
        $messageForm = $this->createForm(MessageType::class, $message);

        // gets data from form, which is why we can check if it's submitted and valid
        $messageForm->handleRequest($request);
        $recipient = $location->getOwner();
        $message->setRecipient($recipient);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $sender = $message->getSender();
         
            // the sender is the current user
            //$message->setSender($this->getUser());
            $message->setSender($sender);
            

            // $recipient = $repository->findMessageRecipient($user, $location->getId());
            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent");
            return $this->redirectToRoute("show_location", ["id" => $location->getId()]);
        }

        return $this->render("message/send.html.twig", [
            'recipient' => $recipient,
            "messageForm" => $messageForm->createView()
        ]);
    }




    /**
     * @Route("/sent/{id}", name="sent")
     */
    public function sent(User $user): Response
    {
        return $this->render('message/sent.html.twig', [
            "user" => $user
        ]);
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(ManagerRegistry $doctrine, Message $message): Response
    {
        $message->setIsRead(true);
        $em = $doctrine->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('message/read.html.twig', compact("message"));
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(ManagerRegistry $doctrine, Message $message, User $user): Response
    {
        $em = $doctrine->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("app_message", ["id" => $user->getId()]);
    }
}
