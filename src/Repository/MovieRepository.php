<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @return Movie[]
     */
    public function findAllExceptYear(int $year): array
    {
        $qb = $this->createQueryBuilder('movie');

        $qb->andWhere('movie.yearFeasted != :year');
        $qb->setParameter('year', $year);

        $qb->orderBy('movie.yearFeasted', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
