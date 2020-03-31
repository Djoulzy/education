<?php

namespace App\Repository;

use App\Entity\VerbeTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VerbeTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerbeTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerbeTemplate[]    findAll()
 * @method VerbeTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerbeTemplateRepository extends ServiceEntityRepository
{
    private $conn;
    private $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerbeTemplate::class);
        $this->manager = $this->getEntityManager();
        $this->conn = $this->manager->getConnection();
    }

    public function insert(string $name, string $infinitif, array $data)
    {
        $values = array(
            'name' => $name,
            'infinitive' => $infinitif,
            'data' => json_encode($data)
        );

        try {
            $this->conn->insert('verbe_template', $values);
            return true;
        } catch(\Doctrine\DBAL\DBALException $e) {
            return false;
        }
    }


    // /**
    //  * @return VerbeTemplate[] Returns an array of VerbeTemplate objects
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
    public function findOneBySomeField($value): ?VerbeTemplate
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
