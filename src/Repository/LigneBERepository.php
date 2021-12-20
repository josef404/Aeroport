<?php

namespace App\Repository;

use App\Entity\LigneBE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneBE|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneBE|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneBE[]    findAll()
 * @method LigneBE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneBERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneBE::class);
    }

    // /**
    //  * @return LigneBE[] Returns an array of LigneBE objects
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
    public function findOneBySomeField($value): ?LigneBE
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
