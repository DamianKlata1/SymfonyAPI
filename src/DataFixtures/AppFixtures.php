<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('user@user.pl');
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'password'
        ));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}
