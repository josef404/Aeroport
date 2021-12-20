<?php

namespace App\Repository;

use App\Entity\Magasiniers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Magasiniers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Magasiniers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Magasiniers[]    findAll()
 * @method Magasiniers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasiniersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Magasiniers::class);
    }

    // /**
    //  * @return Magasiniers[] Returns an array of Magasiniers objects
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
    public function findOneBySomeField($value): ?Magasiniers
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
