<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Security;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $conn;
    private $manager;
    private $session;
    private $security;
    private $encoder;

    public function __construct(ManagerRegistry $registry, SessionInterface $session, Security $security, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->manager = $this->getEntityManager();
        $this->conn = $this->manager->getConnection();
        $this->security = $security;
        $this->session = $session;
        $this->encoder = $encoder;
    }

    //////////////////////////////////////////////////////////
    // COMMAND functions
    //////////////////////////////////////////////////////////

    private function _createUsers($full, $nick, $mail, $pass, $roles)
    {
        $user = new User();
        $user->setFullname($full);
        $user->setNickname($nick);
        $user->setEmail($mail);
        $user->setRoles($roles);
        $user->setPassword($this->encoder->encodePassword($user, $pass));
        $user->setDisabled(true);

        $this->manager->persist($user);
        $this->manager->flush();
        return true;
    }

    public function createAdmins()
    {
        return $this->_createUsers('Jules Marusi', 'jules', 'j@b.fr', 'ok', ['ROLE_ADMIN']);
    }

    public function createUser($full, $nick, $mail, $password)
    {
        return $this->_createUsers($full, $nick, $mail, $password, ["ROLE_DEMO"]);
    }

    //////////////////////////////////////////////////////////
    // RULES functions
    //////////////////////////////////////////////////////////
}
