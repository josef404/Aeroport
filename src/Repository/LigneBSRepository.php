<?php

namespace App\Repository;

use App\Entity\LigneBS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneBS|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneBS|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneBS[]    findAll()
 * @method LigneBS[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneBSRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneBS::class);
    }

    // /**
    //  * @return LigneBS[] Returns an array of LigneBS objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneBS
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
