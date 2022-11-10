<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostLikeRepository;

/**
 * @ORM\Entity(repositoryClass=PostLikeRepository::class)
 */
class PostLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
