<?php

namespace App\Repository;

use App\Entity\FicheIntervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheIntervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheIntervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheIntervention[]    findAll()
 * @method FicheIntervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheInterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheIntervention::class);
    }






    /*
    public function findOneBySomeField($value): ?FicheIntervention
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
