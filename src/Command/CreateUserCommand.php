<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class CreateUserCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->addOption('username', null, InputOption::VALUE_REQUIRED, 'User name', null)
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Password', "")
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Email', null)
            ->setDescription('Create new User')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln('You are about to create a new user. All of this users will be admin users.');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $username = $input->getOption('username');
        $password = $input->getOption('password');
        $email = $input->getOption('email');

        $user_search = $em->getRepository('App:User')->findOneBy(['username' => $username]);

        if ($user_search){
            $output->writeln("This user already exists, choose another one");
            return 0;
        }

        $newUser = $this->createUser(
            $username,
            $password,
            $email
        );

        $em->persist($newUser);
        $em->flush();

        $output->writeln("User {$newUser->getId()} created");

        return 0;
    }

    private function createUser($username, $plainPassword, $email)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setEmail($email);

        return $user;
    }
}