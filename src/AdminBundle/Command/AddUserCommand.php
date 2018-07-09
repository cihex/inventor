<?php

namespace AdminBundle\Command;

use AdminBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Console\Input\InputDefinition;


/**
 * Class AddUserCommand
 * @package AdminBundle\Command
 */
class AddUserCommand extends ContainerAwareCommand
{
    /**
     * AddUserCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function configure()
    {
        $this->setName('app:add-user')
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->setDescription('Prints some text into the console with given parameters.')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('email', null, InputOption::VALUE_REQUIRED, 'Username/email'),
                    new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'Password')
                ])
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $password = $this->getContainer()->get('security.password_encoder')
            ->encodePassword($user, $input->getOption('password'));
        $user->setAdmin(true)
            ->setEmail($input->getOption('email'))
            ->setUsername($input->getOption('email'))
            ->setPassword($password)
            ->setIsActive(1)
            ->setNew(true);
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        $output->writeln('Dodano użytkownika ' . $input->getOption('email'));
    }
}