<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"name_user"}, message="Username is already taken")
 * (picture_user=Images::class, mappedBy="user", cascade={"persist"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=5, max=15, minMessage="Username must be at least 5 characters", maxMessage="Username can't exceed 15 characters")
     */
    private $name_user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $picture_user;
    


    /**
     * @ORM\Column(type="string", length=50) 
     */
    private $phone_user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $registerDate;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $country_user;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city_user;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $postcode_user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isVerified;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="provider_service")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="owner_location")
     */
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity=CommentUserLocation::class, mappedBy="commenter")
     */
    private $commentUserLocations;

    /**
     * @ORM\OneToMany(targetEntity=CommentUserService::class, mappedBy="commenter")
     */
    private $commentUserServices;

    /**
     * @ORM\OneToMany(targetEntity=BookService::class, mappedBy="serviceClient")
     */
    private $bookServices;

    /**
     * @ORM\OneToMany(targetEntity=BookLocation::class, mappedBy="locationClient")
     */
    private $bookLocations;

    /**
     * @ORM\OneToMany(targetEntity=InstantMessage::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sent;

    /**
     * @ORM\OneToMany(targetEntity=InstantMessage::class, mappedBy="recipient", orphanRemoval=true)
     */
    private $received;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="user")
     */
    private $likes;



    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->commentUserLocations = new ArrayCollection();
        $this->commentUserServices = new ArrayCollection();
        $this->bookServices = new ArrayCollection();
        $this->bookLocations = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
        $this->likes = new ArrayCollection();
    
    }

    

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->name_user;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole($role) {
        $this->roles[] = $role;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNameUser(): ?string
    {
        return $this->name_user;
    }

    public function setNameUser(string $name_user): self
    {
        $this->name_user = $name_user;

        return $this;
    }

    public function getPictureUser(): ?string
    {
        return $this->picture_user;
    }

    public function setPictureUser(string $picture_user): self
    {
        $this->picture_user = $picture_user;

        return $this;
    }

    public function getPhoneUser(): ?string
    {
        return $this->phone_user;
    }

    public function setPhoneUser(?string $phone_user): self
    {
        $this->phone_user = $phone_user;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function getCountryUser(): ?string
    {
        return $this->country_user;
    }

    public function setCountryUser(string $country_user): self
    {
        $this->country_user = $country_user;

        return $this;
    }

    public function getCityUser(): ?string
    {
        return $this->city_user;
    }

    public function setCityUser(string $city_user): self
    {
        $this->city_user = $city_user;

        return $this;
    }

    public function getPostcodeUser(): ?string
    {
        return $this->postcode_user;
    }

    public function setPostcodeUser(string $postcode_user): self
    {
        $this->postcode_user = $postcode_user;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    
    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setProviderService($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getProviderService() === $this) {
                $service->setProviderService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setOwnerLocation($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getOwnerLocation() === $this) {
                $location->setOwnerLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentUserLocation>
     */
    public function getCommentUserLocations(): Collection
    {
        return $this->commentUserLocations;
    }

    public function addCommentUserLocation(CommentUserLocation $commentUserLocation): self
    {
        if (!$this->commentUserLocations->contains($commentUserLocation)) {
            $this->commentUserLocations[] = $commentUserLocation;
            $commentUserLocation->setCommenter($this);
        }

        return $this;
    }

    public function removeCommentUserLocation(CommentUserLocation $commentUserLocation): self
    {
        if ($this->commentUserLocations->removeElement($commentUserLocation)) {
            // set the owning side to null (unless already changed)
            if ($commentUserLocation->getCommenter() === $this) {
                $commentUserLocation->setCommenter(null);
            }
        }

        return $this;
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
            $commentUserService->setCommenter($this);
        }

        return $this;
    }

    public function removeCommentUserService(CommentUserService $commentUserService): self
    {
        if ($this->commentUserServices->removeElement($commentUserService)) {
            // set the owning side to null (unless already changed)
            if ($commentUserService->getCommenter() === $this) {
                $commentUserService->setCommenter(null);
            }
        }

        return $this;
    }


    public function __toString(){
        return $this->name_user;
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
            $bookService->setServiceClient($this);
        }

        return $this;
    }

    public function removeBookService(BookService $bookService): self
    {
        if ($this->bookServices->removeElement($bookService)) {
            // set the owning side to null (unless already changed)
            if ($bookService->getServiceClient() === $this) {
                $bookService->setServiceClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BookLocation>
     */
    public function getBookLocations(): Collection
    {
        return $this->bookLocations;
    }

    public function addBookLocation(BookLocation $bookLocation): self
    {
        if (!$this->bookLocations->contains($bookLocation)) {
            $this->bookLocations[] = $bookLocation;
            $bookLocation->setLocationClient($this);
        }

        return $this;
    }

    public function removeBookLocation(BookLocation $bookLocation): self
    {
        if ($this->bookLocations->removeElement($bookLocation)) {
            // set the owning side to null (unless already changed)
            if ($bookLocation->getLocationClient() === $this) {
                $bookLocation->setLocationClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InstantMessage>
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(InstantMessage $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setSender($this);
        }

        return $this;
    }

    public function removeSent(InstantMessage $sent): self
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getSender() === $this) {
                $sent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InstantMessage>
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(InstantMessage $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received[] = $received;
            $received->setRecipient($this);
        }

        return $this;
    }

    public function removeReceived(InstantMessage $received): self
    {
        if ($this->received->removeElement($received)) {
            // set the owning side to null (unless already changed)
            if ($received->getRecipient() === $this) {
                $received->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PostLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

 

    


 
}
