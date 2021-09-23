<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    protected static $defaultDescription = 'Create an admin user.';
    private ?EntityManagerInterface $entityManager;
    private ?UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        string $name = null,
        ?EntityManagerInterface $entityManager = null,
        ?UserPasswordEncoderInterface $userPasswordEncoder = null
    ) {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = new User();

        $user->setRoles(['ROLE_ADMIN']);

        $email = $io->ask('What email?');

        $user->setEmail($email);

        $password = $io->askHidden('What password? (this is hidden)');
        $passwordConfirm = $io->askHidden('Confirm password.');

        if ($password !== $passwordConfirm) {
            $io->error('Doesn\'t match.');
            return 1;
        }

        $user->setPassword(
            $this->userPasswordEncoder->encodePassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Admin user created.');

        return 0;
    }
}
