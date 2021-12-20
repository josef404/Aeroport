<?php

namespace App\Repository;

use App\Entity\PiecesMachine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PiecesMachine|null find($id, $lockMode = null, $lockVersion = null)
 * @method PiecesMachine|null findOneBy(array $criteria, array $orderBy = null)
 * @method PiecesMachine[]    findAll()
 * @method PiecesMachine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PiecesMachineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PiecesMachine::class);
    }

    // /**
    //  * @return PiecesMachine[] Returns an array of PiecesMachine objects
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
    public function findOneBySomeField($value): ?PiecesMachine
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
