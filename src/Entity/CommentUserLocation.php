<?php

namespace App\Entity;

use ORM\JoinColumn;
use App\Entity\User;
use DateTimeInterface;
use App\Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentUserLocationRepository;

/**
 * @ORM\Entity(repositoryClass=CommentUserLocationRepository::class)
 */
class CommentUserLocation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_comment;

    /**
     * @ORM\Column(type="text")
     */
    private $text_comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentUserLocations")
     * @ORM\JoinColumn(nullable=true)
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $commenter;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="commentUserLocations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateComment(): ?\DateTimeInterface
    {
        return $this->date_comment;
    }

    public function setDateComment(DateTimeInterface $date_comment): self
    {
        $this->date_comment = $date_comment;

        return $this;
    }

    public function getTextComment(): ?string
    {
        return $this->text_comment;
    }

    public function setTextComment(string $text_comment): self
    {
        $this->text_comment = $text_comment;

        return $this;
    }

    public function getCommenter(): ?User
    {
        return $this->commenter;
    }

    public function setCommenter(?User $commenter): self
    {
        $this->commenter = $commenter;

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
