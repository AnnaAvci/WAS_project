<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Location;
use App\Entity\PostLike;
use Doctrine\ORM\Mapping\PostLoad;
use App\Repository\PostLikeRepository;
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


    /**
     * Allows to like/unlike a service
     * @Route("/photoshoot/{id}/like", name="service_like")
     * @param Service $service
     * @param ManagerRegistry $doctrine
     * @param PostLikeRepository $repo
     * @return Response
     */
    public function serviceLike(Service $service, ManagerRegistry $doctrine, PostLikeRepository $repo): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        // if user is not connected, error 403
        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Please log in to like a post"
            ], 403);
        }

        // unlike service if already liked and count new nb of likes, success code 200 = an http status
        if ($service->isLikedByUser($user)) {
            $like = $repo->findOneBy([
                'service' => $service,
                'user' => $user
            ]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json([
                'code' => 200,
                'message' => "You no longer like this photoshoot",
                'likes' => $repo->count(['service' => $service])

            ], 200);
        }

        $like = new PostLike();
        $like->setService($service)
            ->setUser($user);
        $entityManager->persist($like);
        $entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => "You like this photoshoot",
            'likes' => $repo->count(['service' => $service])

        ], 200);
    }


    /**
     * Allows to like/unlike a location
     * @Route("/location/{id}/like", name="location_like")
     * @param Location $location
     * @param ManagerRegistry $doctrine
     * @param PostLikeRepository $repo
     * @return Response
     */
    public function locationLike(Location $location, ManagerRegistry $doctrine, PostLikeRepository $repo): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        // if user is not connected, error 403
        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Please log in to like a post"
            ], 403);
        }

        // unlike location if already liked and count new nb of likes, success code 200 = an http status
        if ($location->isLikedByUser($user)) {
            $like = $repo->findOneBy([
                'location' => $location,
                'user' => $user
            ]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json([
                'code' => 200,
                'message' => "You no longer like this location",
                'likes' => $repo->count(['location' => $location])

            ], 200);
        }

        $like = new PostLike();
        $like->setLocation($location)
            ->setUser($user);
        $entityManager->persist($like);
        $entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => "You like this location",
            'likes' => $repo->count(['location' => $location])

        ], 200);
    }

}
