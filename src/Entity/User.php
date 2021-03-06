<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="Cette adresse email est déjà utilisée")
 * @UniqueEntity(fields={"username"}, message="Ce pseudo est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Veuillez renseigner une adresse mail")
     * @Assert\Email(message="Veuillez renseigner une adresse email valide")
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Veuillez choisir un pseudo")
     * @Assert\Length(min=1, max=20, minMessage="Votre description doit faire minimum {{ limit }} caractère", maxMessage="Votre description doit faire maximum {{ limit }} caractère")
     * @Assert\Regex(pattern="/[a-zA-Z0-9\-]+$/", message="Votre pseudo ne peut contenir que des tirets ('-'), des chiffres, ou des lettres")
     */
    private string $username;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="owner", orphanRemoval=true)
     */
    private Collection $pictures;

    /**
     * @ORM\ManyToMany(targetEntity=Picture::class, inversedBy="userLikes")
     */
    private Collection $likes;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setOwner($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getOwner() === $this) {
                $picture->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Picture $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
        }

        return $this;
    }

    public function removeLike(Picture $like): self
    {
        $this->likes->removeElement($like);

        return $this;
    }

    public function hasLiked(Picture $picture): bool
    {
        if ($this->likes->contains($picture)) {
            return true;
        }

        return false;
    }
}
