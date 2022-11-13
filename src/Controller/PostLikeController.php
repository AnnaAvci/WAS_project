<?php

namespace App\Controller;

use App\Entity\PostLike;
use App\Entity\User;
use Doctrine\ORM\Mapping\PostLoad;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostLikeController extends AbstractController
{
    /**
     * @Route("/favourites/{id}", name="app_like")
     */
    public function index(ManagerRegistry $doctrine, Request $request, User $user): Response
    {
       $likes = $user->getLikes();
       $locations = [];
       $services = [];

       foreach($likes as $postLike){
            $location = $postLike->getLocation();
            $service = $postLike->getService();

            if($location){
                $locations[] = $location;
            }else if($service){
                $services[] = $service;
            }
       }
       
        return $this->render('post_like/index.html.twig', [
            'services' => $services,
            'locations' => $locations,
        ]);
    }






}
