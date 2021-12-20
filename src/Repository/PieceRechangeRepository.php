<?php

namespace App\Repository;

use App\Entity\PieceRechange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PieceRechange|null find($id, $lockMode = null, $lockVersion = null)
 * @method PieceRechange|null findOneBy(array $criteria, array $orderBy = null)
 * @method PieceRechange[]    findAll()
 * @method PieceRechange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PieceRechangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PieceRechange::class);
    }

    // /**
    //  * @return PieceRechange[] Returns an array of PieceRechange objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PieceRechange
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
