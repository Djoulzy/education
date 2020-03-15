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


class addUserCommand extends Command
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
            ->setName('addUser')
            ->setDescription('Create User');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $lastname_ask = new Question("Nom : ");
        $firstname_ask = new Question("Prénom : ");
        $username_ask = new Question("Renseigner le mail d'autentification : ");
        $password_ask = new Question("Renseigner le mot de passe d'autentification : ");
        $password_ask->setHidden(true);

        $lastname = $helper->ask($input, $output, $lastname_ask);
        $firstname = $helper->ask($input, $output, $firstname_ask);
        $username = $helper->ask($input, $output, $username_ask);
        $password = $helper->ask($input, $output, $password_ask);

        if ($username && $password) {
            $this->user->createUser($lastname, $firstname, $username, $password);
        }
    }
}