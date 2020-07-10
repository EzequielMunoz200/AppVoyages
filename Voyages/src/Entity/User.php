<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Ce compte existe déjà")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(allowNull=false, message="Obligatoire")
     * @Assert\Email(
     * message = "Email non valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(allowNull=false, message="Obligatoire")
     * @Assert\Regex(
     *  pattern = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#",
     *  match=true,
     *  message="Au moins 8 caractères, au moins une majuscule, au moins un caractère spécial et au moins un chiffre")
     * @Assert\Length(
     *  min = 8,
     *  max = 4096,
     *  minMessage="8 caractères minimum")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false, message="Obligatoire")
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum",
     *      allowEmptyString = false
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(allowNull=false, message="Obligatoire")
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum",
     *      allowEmptyString = false
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "{{ limit }} caractères minimum",
     *      maxMessage = "{{ limit }} caractères maximum",
     *      allowEmptyString = false
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(allowNull=false, message="Obligatoire")
     * @Assert\LessThan("-18 years", message="Minimum 18 ans")
     * 
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

   /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 0})
     */
    private $points;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=Language::class, inversedBy="users")
     */
    private $languages;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, inversedBy="users")
     */
    private $badges;

    /**
     * @ORM\ManyToMany(targetEntity=CityList::class, inversedBy="users")
     */
    private $cityLists;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="author", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=ReviewLike::class, mappedBy="user", orphanRemoval=true)
     */
    private $reviewLikes;

    /**
     * @ORM\OneToMany(targetEntity=CityLike::class, mappedBy="user", orphanRemoval=true)
     */
    private $cityLikes;

    /**
     * @ORM\OneToMany(targetEntity=UserLike::class, mappedBy="userSource", orphanRemoval=true)
     */
    private $userLikes;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->cityLists = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reviewLikes = new ArrayCollection();
        $this->cityLikes = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
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
        return (string) $this->email;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->languages->contains($language)) {
            $this->languages->removeElement($language);
        }

        return $this;
    }

    /**
     * @return Collection|Badge[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badges->contains($badge)) {
            $this->badges->removeElement($badge);
        }

        return $this;
    }

    /**
     * @return Collection|CityList[]
     */
    public function getCityLists(): Collection
    {
        return $this->cityLists;
    }

    public function addCityList(CityList $cityList): self
    {
        if (!$this->cityLists->contains($cityList)) {
            $this->cityLists[] = $cityList;
        }

        return $this;
    }

    public function removeCityList(CityList $cityList): self
    {
        if ($this->cityLists->contains($cityList)) {
            $this->cityLists->removeElement($cityList);
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setAuthor($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReviewLike[]
     */
    public function getReviewLikes(): Collection
    {
        return $this->reviewLikes;
    }

    public function addReviewLike(ReviewLike $reviewLike): self
    {
        if (!$this->reviewLikes->contains($reviewLike)) {
            $this->reviewLikes[] = $reviewLike;
            $reviewLike->setUser($this);
        }

        return $this;
    }

    public function removeReviewLike(ReviewLike $reviewLike): self
    {
        if ($this->reviewLikes->contains($reviewLike)) {
            $this->reviewLikes->removeElement($reviewLike);
            // set the owning side to null (unless already changed)
            if ($reviewLike->getUser() === $this) {
                $reviewLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CityLike[]
     */
    public function getCityLikes(): Collection
    {
        return $this->cityLikes;
    }

    public function addCityLike(CityLike $cityLike): self
    {
        if (!$this->cityLikes->contains($cityLike)) {
            $this->cityLikes[] = $cityLike;
            $cityLike->setUser($this);
        }

        return $this;
    }

    public function removeCityLike(CityLike $cityLike): self
    {
        if ($this->cityLikes->contains($cityLike)) {
            $this->cityLikes->removeElement($cityLike);
            // set the owning side to null (unless already changed)
            if ($cityLike->getUser() === $this) {
                $cityLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserLike[]
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLike $userLike): self
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes[] = $userLike;
            $userLike->setUserSource($this);
        }

        return $this;
    }

    public function removeUserLike(UserLike $userLike): self
    {
        if ($this->userLikes->contains($userLike)) {
            $this->userLikes->removeElement($userLike);
            // set the owning side to null (unless already changed)
            if ($userLike->getUserSource() === $this) {
                $userLike->setUserSource(null);
            }
        }

        return $this;
    }
}
