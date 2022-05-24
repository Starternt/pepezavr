<?php

namespace App\DataFixtures;

use App\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements ORMFixtureInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin')
            ->setEmail('admin@admin.com')
            ->setPassword($this->hasher->hashPassword($user, '123'))
            ->setName('Admin');

        $manager->persist($user);

        $manager->flush();
    }
}
