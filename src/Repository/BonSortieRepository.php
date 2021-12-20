<?php

namespace App\Repository;

use App\Entity\BonSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BonSortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method BonSortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method BonSortie[]    findAll()
 * @method BonSortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonSortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BonSortie::class);
    }

    // /**
    //  * @return BonSortie[] Returns an array of BonSortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BonSortie
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
