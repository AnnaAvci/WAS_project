<?php

namespace App\Controller;

use App\Entity\User;
use App\Data\SearchData;
use App\Entity\Location;
use App\Form\SearchForm;
use App\Form\ContactType;
use App\Data\SearchServiceData;
use App\Form\SearchServiceForm;
use Symfony\Component\Mime\Email;
use App\Repository\ServiceRepository;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, LocationRepository $lr, ServiceRepository $sr, Request $request, 
    PaginatorInterface $paginator): Response
    {      
        // search bar for locations
        $dataSearch = new SearchData();
        $formSearch = $this->createForm(SearchForm::class, $dataSearch);
        $formSearch->handleRequest($request);

        $data = $lr->findSearch($dataSearch);
       
        shuffle($data); 
        $locations = $paginator->paginate($data, $request->query->getInt("page", 1), 3); 
       
        // search bar for services
        $dataSearchService = new SearchServiceData();
        $formSearchService = $this->createForm(SearchServiceForm::class, $dataSearchService);
        $formSearchService->handleRequest($request);

        $dataService = $sr->findSearch($dataSearchService);
       
        shuffle($dataService); 
        $services = $paginator->paginate($dataService, $request->query->getInt("page", 1), 3); 
       
        return $this->render('home/index.html.twig', [

            'locations' => $locations,
            'services' => $services,
            'formSearch' => $formSearch->createView(), 
            'formSearchService' => $formSearchService->createView() 
        ]);
    }

    /**
     *@Route("/terms-and-conditions", name="terms")
     *
     * @return void
     */
    public function terms (){
        return $this->render('home/terms.html.twig');
    }

    /**
     *@Route("/contact-form", name="contact-form")
     *
     */
    public function contactForm (Request $request, MailerInterface $mailer){
        $contactForm = $this->createForm(ContactType::class);
        $contact= $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()){
            // if($this->getUser)
            $email = (new TemplatedEmail())
                    ->from($contact->get('email')->getData())
                    ->to('avci.anne@gmail.com')
                    ->htmlTemplate('home/contact-template.html.twig')
                    ->context([
                        // email is a reserved word
                        'mail'=> $contact->get('email')->getData(), 
                        'subject'=> $contact->get('subject')->getData(), 
                        'message'=> $contact->get('message')->getData() 
                    ]);
            $mailer->send($email);
            $this->addFlash('message', 'Your message has been sent');

            return $this->redirectToRoute('contact-form');
        }
        return $this->render('home/contact.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}