<?php


namespace App\Event;


use App\Entity\Movie;
use App\OMDB\API;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieSubscriber implements EventSubscriberInterface
{
    private API $omdbApi;

    public function __construct(API $omdbApi)
    {
        $this->omdbApi = $omdbApi;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof Movie) {
            $this->updateMovieFromOmdb($object);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();
        if ($object instanceof Movie) {
            $this->updateMovieFromOmdb($object);
        }
    }

    private function updateMovieFromOmdb(Movie $movie): void
    {
        if ($movie->getRuntime() && $movie->getTitle())
            return;

        $omdbMovie = $this->omdbApi->getMovie($movie->getImdb());
        $dateInterval = \DateInterval::createFromDateString($omdbMovie->Runtime);
        $movie->setRuntime($dateInterval->i);
        $movie->setTitle($omdbMovie->Title);
    }
}