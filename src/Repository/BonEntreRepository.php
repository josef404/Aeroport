<?php

namespace App\Repository;

use App\Entity\BonEntre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BonEntre|null find($id, $lockMode = null, $lockVersion = null)
 * @method BonEntre|null findOneBy(array $criteria, array $orderBy = null)
 * @method BonEntre[]    findAll()
 * @method BonEntre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonEntreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BonEntre::class);
    }

    // /**
    //  * @return BonEntre[] Returns an array of BonEntre objects
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
    public function findOneBySomeField($value): ?BonEntre
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
