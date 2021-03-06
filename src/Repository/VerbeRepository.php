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
    private $conn;
    private $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Verbe::class);
        $this->manager = $this->getEntityManager();
        $this->conn = $this->manager->getConnection();
    }

    public function insert(string $lang, string $fr, string $inf, string $form1, string $form2, int $level)
    {
        $values = array(
            'lang' => $lang,
            'fr' => $fr,
            'infinitif' => $inf,
            'form1' => $form1,
            'form2' => $form2,
            'level' => $level
        );

        try {
            $this->conn->insert('verbe', $values);
            return true;
        } catch(\Doctrine\DBAL\DBALException $e) {
            return false;
        }
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
