<?php

namespace App\Entity;

use App\Repository\CommentUserServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentUserServiceRepository::class)
 */
class CommentUserService
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentUserServices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commenter;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="commentUserServices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateComment(): ?\DateTimeInterface
    {
        return $this->date_comment;
    }

    public function setDateComment(\DateTimeInterface $date_comment): self
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

    public function getservice(): ?Service
    {
        return $this->service;
    }

    public function setservice(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }
}
