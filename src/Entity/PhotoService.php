<?php

namespace App\Entity;

use App\Repository\PhotoServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoServiceRepository::class)
 */
class PhotoService
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_photo;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="photoServices")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $service;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamePhoto(): ?string
    {
        return $this->name_photo;
    }

    public function setNamePhoto(string $name_photo): self
    {
        $this->name_photo = $name_photo;

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
}
