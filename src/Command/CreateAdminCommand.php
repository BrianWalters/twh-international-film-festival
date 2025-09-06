<?php

namespace App\Command;

use App\Entity\User;
use App\Service\UserManager;
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
    private UserManager $userManager;

    public function __construct(
        UserManager $userManager
    ) {
        parent::__construct();
        $this->userManager = $userManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask('What email?');
        $password = $io->askHidden('What password? (this is hidden)');
        $passwordConfirm = $io->askHidden('Confirm password.');

        if ($password !== $passwordConfirm) {
            $io->error('Doesn\'t match.');
            return 1;
        }

        $this->userManager->makeAdmin($email, $password);

        $io->success('Admin user created.');

        return 0;
    }
}
