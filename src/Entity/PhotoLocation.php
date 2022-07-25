<?php

namespace App\Entity;

use App\Repository\PhotoLocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoLocationRepository::class)
 */
class PhotoLocation
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
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="photoLocations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * 
     */
    private $location;

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

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}
