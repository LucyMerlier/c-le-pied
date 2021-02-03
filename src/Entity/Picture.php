<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez ajouter une image")
     */
    private string $imageURL;

    /**
     * @Vich\UploadableField(mapping="picture_file", fileNameProperty="photo")
     * @Assert\NotBlank(message="Veuillez ajouter une image")
     */
    private File $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez ajouter une description")
     */
    private string $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $owner;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="picture", orphanRemoval=true)
     */
    private Collection $comments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function setImageURL(string $imageURL): self
    {
        $this->imageURL = $imageURL;

        return $this;
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
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPicture($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPicture() === $this) {
                $comment->setPicture(null);
            }
        }

        return $this;
    }

    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile): self
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
