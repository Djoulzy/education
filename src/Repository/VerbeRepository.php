<?php

namespace App\Repository;

use App\Entity\Verbe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Verbe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Verbe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Verbe[]    findAll()
 * @method Verbe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerbeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Verbe::class);
    }

    // /**
    //  * @return Verbe[] Returns an array of Verbe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Verbe
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
