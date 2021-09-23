<?php

namespace App\DataFixtures;

use App\Factory\MovieFactory;
use App\Service\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {
        MovieFactory::createMany(42);

        $this->userManager->makeAdmin('admin@fake.com', 'admin');
    }
}
