<?php


namespace App\ModelTransformer;


use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class MovieTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($movie) {
        return $movie ? $movie->getId() : null;
    }

    public function reverseTransform($id) {
        return $this->entityManager->find(Movie::class, $id);
    }
}