<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"api_v1_city"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_v1_city"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_v1_city"})
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api_v1_city"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_v1_city"})
     */
    private $geonameId;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="city", orphanRemoval=true)
     */
    private $Reviews;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="cities")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=CityLike::class, mappedBy="city", orphanRemoval=true)
     */
    private $cityLikes;

    public function __construct()
    {
        $this->Reviews = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->cityLikes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getGeonameId(): ?int
    {
        return $this->geonameId;
    }

    public function setGeonameId(int $geonameId): self
    {
        $this->geonameId = $geonameId;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->Reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->Reviews->contains($review)) {
            $this->Reviews[] = $review;
            $review->setCity($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->Reviews->contains($review)) {
            $this->Reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getCity() === $this) {
                $review->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addCity($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeCity($this);
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
            $cityLike->setCity($this);
        }

        return $this;
    }

    public function removeCityLike(CityLike $cityLike): self
    {
        if ($this->cityLikes->contains($cityLike)) {
            $this->cityLikes->removeElement($cityLike);
            // set the owning side to null (unless already changed)
            if ($cityLike->getCity() === $this) {
                $cityLike->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"api_v1_city"})
     */
    public function getUrlCity()
    {
        return '/city/' . $this->getGeonameId();
        //return 'http://' . $_SERVER['SERVER_NAME'] . '/city/' . $this->getGeonameId();
    }


    public function isLikedByUser(User $user): bool
    {
        foreach ($this->cityLikes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }

}
