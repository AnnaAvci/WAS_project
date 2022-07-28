<?php

namespace App\Controller;

use App\Entity\User;
use App\Data\SearchData;
use App\Entity\Location;
use App\Form\SearchForm;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, LocationRepository $lr, Request $request, PaginatorInterface $paginator): Response
    {

        $dataSearch = new SearchData();
        $formSearch = $this->createForm(SearchForm::class, $dataSearch);
        $formSearch->handleRequest($request);

        $data = $lr->findSearch($dataSearch);
        shuffle($data); 
        $locations = $paginator->paginate($data, $request->query->getInt("page", 1), 8); 
        
        return $this->render('home/index.html.twig', [
            
            'locations' => $locations,
            'formSearch' => $formSearch->createView() 
        ]);
    }
}
