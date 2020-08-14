<?php

namespace App\Entity;

use App\Repository\ReviewLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewLikeRepository::class)
 */
class ReviewLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Review::class, inversedBy="reviewLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $review;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviewLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __toString()
    {
        return strval($this->id);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReview(): ?Review
    {
        return $this->review;
    }

    public function setReview(?Review $review): self
    {
        $this->review = $review;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
