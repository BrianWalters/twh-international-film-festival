<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $imdb;

    /**
     * @ORM\Column(type="datetimetz", nullable="true")
     * @Assert\DateTime()
     */
    private ?\DateTimeInterface $startTime;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $runtime = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="movie", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $ratings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="movie", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $comments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $lukeBit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank
     */
    private ?int $yearFeasted;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title = null;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title ?? 'Untitled';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImdb(): ?string
    {
        return $this->imdb;
    }

    public function setImdb(?string $imdb): self
    {
        $this->imdb = $imdb;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(?int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setMovie($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getMovie() === $this) {
                $rating->setMovie(null);
            }
        }

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
            $comment->setMovie($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getMovie() === $this) {
                $comment->setMovie(null);
            }
        }

        return $this;
    }

    public function getAverageRating(): float|int
    {
        if (sizeof($this->ratings) === 0)
            return 0;

        $total = array_reduce($this->ratings->toArray(), function($carry, Rating $rating) {
            return $carry + $rating->getScore();
        }, 0);

        return round($total / sizeof($this->ratings), 1);
    }

    public function isMovieActive(): bool
    {
        if (!$this->startTime)
            return false;

        $now = new \DateTime('now');
        $isAfterStartTime = $now->getTimestamp() > $this->getStartTime()->getTimestamp();
        $endTime = $this->getEndTime();
        $isBeforeRuntime = $now->getTimestamp() < $endTime->getTimestamp();
        return $isAfterStartTime && $isBeforeRuntime;
    }

    public function isMovieOver(): bool
    {
        if (!$this->startTime)
            return true;

        $now = new \DateTime('now');
        return $now->getTimestamp() > $this->getEndTime()->getTimestamp();
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        if (!$this->startTime)
            return null;

        $endTime = clone $this->getStartTime();
        $endTime->modify($this->getRuntime() . ' minutes');
        return $endTime;
    }

    public function getLukeBit(): ?string
    {
        return $this->lukeBit;
    }

    public function setLukeBit(?string $lukeBit): self
    {
        $this->lukeBit = $lukeBit;

        return $this;
    }

    public function getYearFeasted(): ?int
    {
        return $this->yearFeasted;
    }

    public function setYearFeasted(?int $yearFeasted): self
    {
        $this->yearFeasted = $yearFeasted;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function isReadOnly(): bool
    {
        if (!$this->startTime)
            return true;

        $now = new \DateTime('now');

        $interval = $this->startTime->diff($now);

        return $interval->days > 14;
    }

    public function getTruncatedLukeBit(): ?string
    {
        if (!$this->lukeBit)
            return null;

        $truncated = substr(strip_tags($this->lukeBit), 0 , 200);

        if (strlen($truncated) === 200)
            $truncated .= '...';

        return html_entity_decode($truncated, ENT_HTML5 | ENT_QUOTES);
    }
}
