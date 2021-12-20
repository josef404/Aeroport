<?php

namespace App\Repository;

use App\Entity\Demontage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Demontage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demontage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demontage[]    findAll()
 * @method Demontage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemontageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demontage::class);
    }

    // /**
    //  * @return Demontage[] Returns an array of Demontage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demontage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
