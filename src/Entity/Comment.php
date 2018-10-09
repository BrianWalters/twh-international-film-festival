<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    use TimestampableEntity;

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
    private $commenter;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommenter(): ?string
    {
        return $this->commenter;
    }

    public function setCommenter(?string $commenter): self
    {
        $this->commenter = $commenter;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getTimeRelativeToMovie(Movie $movie)
    {
        $intervalFromStartTime = $this->createdAt->diff($movie->getStartTime());
        $intervalFromEndTime = $this->createdAt->diff($movie->getEndTime());

        $startTimeIntervalString = $this->makeIntervalTimeString($intervalFromStartTime);
        $endTimeIntervalString = $this->makeIntervalTimeString($intervalFromEndTime);

        if ($this->createdAt->getTimestamp() < $movie->getStartTime()->getTimestamp())
            return $startTimeIntervalString . ' before the movie';
        else if ($this->createdAt->getTimestamp() < $movie->getEndTime()->getTimestamp())
            return $startTimeIntervalString . ' into the movie';
        else
            return $endTimeIntervalString . ' after the movie';
    }

    private function makeIntervalTimeString(\DateInterval $interval)
    {
        $intervalString = '';
        if ($interval->days > 0)
            $intervalString .= $interval->format('%a days ');

        if ($interval->h > 0)
            $intervalString .= $interval->format('%h hours ');

        $intervalString .= $interval->format('%i minutes');

        return $intervalString;
    }
}
