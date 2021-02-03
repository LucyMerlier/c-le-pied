<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use App\Entity\User;
use App\Entity\Picture;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Votre commentaire ne peut pas être vide")
     * @Assert\Length(min=1, max=255, minMessage="Votre description doit faire minimum {{ limit }} caractère", maxMessage="Votre description doit faire maximum {{ limit }} caractère")
     */
    private string $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Picture::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private Picture $picture;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
