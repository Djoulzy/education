<?php
/**
 * Created by PhpStorm.
 * User: shakunie
 * Date: 30/09/2019
 * Time: 09:30
 */

namespace App\Command;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


class addAdminCommand extends Command
{
    private $user;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->user = $entityManager->getRepository(User::class);
    }

    protected function configure()
    {
        $this
            ->setName('addAdmin')
            ->setDescription('Create Admin users');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->user->createAdmins();
    }
}