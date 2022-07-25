<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
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
    private $name_service;


    /**
     * @ORM\Column(type="string", length=50)
     */
    private $country_service;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city_service;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $postcode_service;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provider_service;


    /**
     * @ORM\Column(type="float")
     */
    private $price_service;

    /**
     * @ORM\OneToMany(targetEntity=PhotoService::class, mappedBy="service", cascade={"persist"})
     */
    private $photoServices;

    /**
     * @ORM\OneToMany(targetEntity=CommentUserService::class, mappedBy="service")
     */
    private $commentUserServices;

    /**
     * @ORM\OneToMany(targetEntity=BookService::class, mappedBy="service")
     */
    private $bookServices;



   

    public function __construct()
    {
        $this->photoServices = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->commentUserServices = new ArrayCollection();
        $this->bookServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameService(): ?string
    {
        return $this->name_service;
    }

    public function setNameService(string $name_service): self
    {
        $this->name_service = $name_service;

        return $this;
    }


    public function getCountryService(): ?string
    {
        return $this->country_service;
    }

    public function setCountryService(string $country_service): self
    {
        $this->country_service = $country_service;

        return $this;
    }

    public function getCityService(): ?string
    {
        return $this->city_service;
    }

    public function setCityService(string $city_service): self
    {
        $this->city_service = $city_service;

        return $this;
    }

    public function getPostcodeService(): ?string
    {
        return $this->postcode_service;
    }

    public function setPostcodeService(string $postcode_service): self
    {
        $this->postcode_service = $postcode_service;

        return $this;
    }

    public function getProviderService(): ?User
    {
        return $this->provider_service;
    }

    public function setProviderService(?User $provider_service): self
    {
        $this->provider_service = $provider_service;

        return $this;
    }


    public function getPriceService(): ?float
    {
        return $this->price_service;
    }

    public function setPriceService(float $price_service): self
    {
        $this->price_service = $price_service;

        return $this;
    }

    /**
     * @return Collection<int, PhotoService>
     */
    public function getPhotoServices(): Collection
    {
        return $this->photoServices;
    }

    public function addPhotoService(PhotoService $photoService): self
    {
        if (!$this->photoServices->contains($photoService)) {
            $this->photoServices[] = $photoService;
            $photoService->setService($this);
        }

        return $this;
    }

    public function removePhotoService(PhotoService $photoService): self
    {
        if ($this->photoServices->removeElement($photoService)) {
            // set the owning side to null (unless already changed)
            if ($photoService->getService() === $this) {
                $photoService->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @return Collection<int, CommentUserService>
     */
    public function getCommentUserServices(): Collection
    {
        return $this->commentUserServices;
    }

    public function addCommentUserService(CommentUserService $commentUserService): self
    {
        if (!$this->commentUserServices->contains($commentUserService)) {
            $this->commentUserServices[] = $commentUserService;
            $commentUserService->setService($this);
        }

        return $this;
    }

    public function removeCommentUserService(CommentUserService $commentUserService): self
    {
        if ($this->commentUserServices->removeElement($commentUserService)) {
            // set the owning side to null (unless already changed)
            if ($commentUserService->getService() === $this) {
                $commentUserService->setService(null);
            }
        }

        return $this;
    }
    

    public function __toString(){
        return $this->name_service;
    }

    /**
     * @return Collection<int, BookService>
     */
    public function getBookServices(): Collection
    {
        return $this->bookServices;
    }

    public function addBookService(BookService $bookService): self
    {
        if (!$this->bookServices->contains($bookService)) {
            $this->bookServices[] = $bookService;
            $bookService->setService($this);
        }

        return $this;
    }

    public function removeBookService(BookService $bookService): self
    {
        if ($this->bookServices->removeElement($bookService)) {
            // set the owning side to null (unless already changed)
            if ($bookService->getService() === $this) {
                $bookService->setService(null);
            }
        }

        return $this;
    }

}
