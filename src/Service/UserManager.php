<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private UserPasswordEncoderInterface $userPasswordEncoder;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
    }

    public function makeAdmin(string $email, string $password): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail($email);
        $user->setPassword(
            $this->userPasswordEncoder->encodePassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}