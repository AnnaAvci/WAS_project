<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\PhotoLocation;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\CommentUserLocation;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name_location;


    /**
     * @ORM\Column(type="string", length=50)
     */
    private $country_location;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city_location;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $postcode_location;

    /**
     * @ORM\Column(type="float")
     */
    private $price_location;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="locations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;


    /**
     * @ORM\OneToMany(targetEntity=PhotoLocation::class, mappedBy="location", cascade={"persist"})
     *
     */
    private $photoLocations;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="comment_location")
     */
   /*  private $users; */

    /**
     * @ORM\OneToMany(targetEntity=CommentUserLocation::class, mappedBy="location")
     */
    private $commentUserLocations;

    /**
     * @ORM\OneToMany(targetEntity=LocationBook::class, mappedBy="location")
     */
    private $locationBooks;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="location")
     */
    private $likes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

   

    public function __construct()
    {
        $this->photoLocations = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->commentUserLocations = new ArrayCollection();
        $this->locationBooks = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameLocation(): ?string
    {
        return $this->name_location;
    }

    public function setNameLocation(string $name_location): self
    {
        $this->name_location = $name_location;

        return $this;
    }


    public function getCountryLocation(): ?string
    {
        return $this->country_location;
    }

    public function setCountryLocation(string $country_location): self
    {
        $this->country_location = $country_location;

        return $this;
    }

    public function getCityLocation(): ?string
    {
        return $this->city_location;
    }

    public function setCityLocation(string $city_location): self
    {
        $this->city_location = $city_location;

        return $this;
    }

    public function getPostcodeLocation(): ?string
    {
        return $this->postcode_location;
    }

    public function setPostcodeLocation(string $postcode_location): self
    {
        $this->postcode_location = $postcode_location;

        return $this;
    }

    public function getPriceLocation(): ?float
    {
        return $this->price_location;
    }

    public function setPriceLocation(float $price_location): self
    {
        $this->price_location = $price_location;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }


    /**
     * @return Collection<int, PhotoLocation>
     */
    public function getPhotoLocations(): Collection
    {
        return $this->photoLocations;
    }

    public function addPhotoLocation(PhotoLocation $photoLocation): self
    {
        if (!$this->photoLocations->contains($photoLocation)) {
            $this->photoLocations[] = $photoLocation;
            $photoLocation->setLocation($this);
        }

        return $this;
    }

    public function removePhotoLocation(PhotoLocation $photoLocation): self
    {
        if ($this->photoLocations->removeElement($photoLocation)) {
            // set the owning side to null (unless already changed)
            if ($photoLocation->getLocation() === $this) {
                $photoLocation->setLocation(null);
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
            $commentUserLocation->setLocation($this);
        }

        return $this;
    }

    public function removeCommentUserLocation(CommentUserLocation $commentUserLocation): self
    {
        if ($this->commentUserLocations->removeElement($commentUserLocation)) {
            // set the owning side to null (unless already changed)
            if ($commentUserLocation->getLocation() === $this) {
                $commentUserLocation->setLocation(null);
            }
        }

        return $this;
    }

 
    public function __toString(){
        return $this->name_location;
    }

    /**
     * @return Collection<int, LocationBook>
     */
    public function getLocationBooks(): Collection
    {
        return $this->locationBooks;
    }

    public function addLocationBook(LocationBook $locationBook): self
    {
        if (!$this->locationBooks->contains($locationBook)) {
            $this->locationBooks[] = $locationBook;
            $locationBook->setLocation($this);
        }

        return $this;
    }

    public function removeLocationBook(LocationBook $locationBook): self
    {
        if ($this->locationBooks->removeElement($locationBook)) {
            // set the owning side to null (unless already changed)
            if ($locationBook->getLocation() === $this) {
                $locationBook->setLocation(null);
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
            $like->setLocation($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getLocation() === $this) {
                $like->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * Shows if location is liked by user
     *
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool
    {
        foreach($this->likes as $like){
            if ($like->getUser() === $user) return true;
        }
        return false;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
  

}
