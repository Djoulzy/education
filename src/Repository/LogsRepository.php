<?php

namespace App\Repository;

use App\Entity\Logs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Logs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logs[]    findAll()
 * @method Logs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogsRepository extends ServiceEntityRepository
{
    private $conn;
    private $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Logs::class);
        $this->manager = $this->getEntityManager();
        $this->conn = $this->manager->getConnection();
    }

    public function add($username, $user_id, $user_ip, $success, $reason)
    {
        $log = new Logs();
        $log->setUsername($username);
        $log->setUserId($user_id);
        $log->setLastLogin(new \DateTime('NOW'));
        $log->setIp($user_ip);
        $log->setSuccess($success);
        $log->setReason($reason);

        $this->manager->persist($log);
        $this->manager->flush();
    }

    // /**
    //  * @return Logs[] Returns an array of Logs objects
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
    public function findOneBySomeField($value): ?Logs
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
