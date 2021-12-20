<?php

namespace App\Repository;

use App\Entity\SousFamille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SousFamille|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousFamille|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousFamille[]    findAll()
 * @method SousFamille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousFamilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousFamille::class);
    }

    // /**
    //  * @return SousFamille[] Returns an array of SousFamille objects
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
    public function findOneBySomeField($value): ?SousFamille
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
