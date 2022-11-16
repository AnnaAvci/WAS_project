<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Service;
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
     * @Route("/messages/{id}", name="app_message")
     */
    public function index(ManagerRegistry $doctrine, MessageRepository $repository, Request $request, User $user): Response
    {
        // get all messages received by the current user, order by date starting with the most recent ones
        $messages = $doctrine->getRepository(Message::class)->findBy(["recipient" => $this->getUser()], ["created_at" => "DESC"]);

        return $this->render('message/index.html.twig', [
            'messages'=> $messages,
            'user'=>$user,
        ]);
    }


    /**
     * @Route("/send/location/{id}", name="send_message_location")
     * 
     */
    public function messageToLocation (ManagerRegistry $doctrine, MessageRepository $repository, Request $request, Location $location): Response
    {
        // creating a new message with the help of the form
        $message = new Message;
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);
        $recipient = $location->getOwner();
        $message->setRecipient($recipient);
        $message->setSender($this->getUser());

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
         
            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent");
            return $this->redirectToRoute("app_message", ["id" => $message->getSender()->getId()]);
        }

        return $this->render("message/send.html.twig", [
            'recipient' => $recipient,
            'location'=>$location,
            "messageForm" => $messageForm->createView()
        ]);
    }




    /**
     * @Route("/send/photoshoot/{id}", name="send_message_service")
     * 
     */
    public function messageToService(ManagerRegistry $doctrine, MessageRepository $repository, Request $request, Service $service): Response
    {
        // creating a new message with the help of the form
        $message = new Message;
        $messageForm = $this->createForm(MessageType::class, $message);

        $messageForm->handleRequest($request);
        $recipient = $service->getOwner();
        $message->setRecipient($recipient);
        $message->setSender($this->getUser());

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent");
            return $this->redirectToRoute("app_message", ["id" => $message->getSender()->getId()]);
        }

        return $this->render("message/send.html.twig", [
            'recipient' => $recipient,
            'service' => $service,
            "messageForm" => $messageForm->createView()
        ]);
    }




    /**
     * @Route("/send/client/{id}", name="send_message_client")
     * 
     */
    public function messageToLocationClient (ManagerRegistry $doctrine, Request $request, User $user): Response
    {
        // creating a new message with the help of the form
        $message = new Message;
        $messageForm = $this->createForm(MessageType::class, $message);
        $sender = $this->getUser();

        $messageForm->handleRequest($request);
        $message->setRecipient($user);
        $message->setSender($sender);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
         
            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent");
            return $this->redirectToRoute("app_message", ["id" => $sender->getId()]);
        }

        return $this->render("message/send.html.twig", [
            "messageForm" => $messageForm->createView()
        ]);
    }




    /**
     * @Route("/sent/{id}", name="sent")
     */
    public function sent(ManagerRegistry $doctrine, User $user): Response
    {
        $messages = $doctrine->getRepository(Message::class)->findBy(["sender" => $this->getUser()], ["created_at" => "DESC"]);
        return $this->render('message/sent.html.twig', [
            "user" => $user,
            "messages"=>$messages
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
     * @Route("/delete/received/{id}", name="deleteReceived")
     */
    public function deleteReceived(ManagerRegistry $doctrine, Message $message): Response
    {
        $user = $message->getRecipient();
        $em = $doctrine->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("app_message", ["id" => $user->getId()]);
    }
    /**
     * @Route("/delete/sent/{id}", name="deleteSent")
     */
    public function deleteSent(ManagerRegistry $doctrine, Message $message): Response
    {
        $user = $message->getSender();
        $em = $doctrine->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("sent", ["id" => $user->getId()]);
    }
}
