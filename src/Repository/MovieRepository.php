<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findAllWithRelations()
    {
        return $this->createQueryBuilder("m")
            ->addSelect("c, r")
            ->leftJoin("m.ratings", "r")
            ->leftJoin("m.categories", "c")
            ->getQuery()
            ->getResult();
    }

    public function findBestMoviesByAvgRatings(?int $count = 3)
    {
        return $this->findForStats("AVG(r.notation)", "DESC", $count);
    }

    public function findWorstMoviesByAvgRatings(?int $count = 3)
    {
        return $this->findForStats("AVG(r.notation)", "ASC", $count);
    }

    public function findLastReleasedMovies(?int $count = 3)
    {
        return $this->findForStats("m.releaseAt", "DESC", $count);
    }

    protected function findForStats(string $order, string $direction, ?int $count = 3)
    {
        return $this->createQueryBuilder("m")
            ->leftJoin("m.ratings", "r")
            ->orderBy($order, $direction)
            ->groupBy("m")
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    public function findBySearch(string $search)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.categories', 'c')
            ->innerJoin('m.director', 'd')
            ->innerJoin('m.actors', 'a')
            ->orWhere('m.title LIKE :search')
            ->orWhere('m.synopsis LIKE :search')
            ->orWhere('c.title LIKE :search')
            ->orWhere('d.firstName LIKE :search')
            ->orWhere('d.lastName LIKE :search')
            ->orWhere('a.firstName LIKE :search')
            ->orWhere('a.lastName LIKE :search')
            ->setParameter('search', "%$search%")
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
