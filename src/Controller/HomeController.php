<?php

namespace App\Controller;

use App\Entity\User;
use App\Data\SearchData;
use App\Entity\Location;
use App\Form\SearchForm;
use App\Data\SearchServiceData;
use App\Form\SearchServiceForm;
use App\Repository\ServiceRepository;
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
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, LocationRepository $lr, ServiceRepository $sr, Request $request, PaginatorInterface $paginator): Response
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


}
