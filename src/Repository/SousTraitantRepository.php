<?php

namespace App\Repository;

use App\Entity\SousTraitant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SousTraitant|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousTraitant|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousTraitant[]    findAll()
 * @method SousTraitant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousTraitantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousTraitant::class);
    }

    // /**
    //  * @return SousTraitant[] Returns an array of SousTraitant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SousTraitant
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
