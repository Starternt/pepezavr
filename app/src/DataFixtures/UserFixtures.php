<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
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
        foreach ($this->getUsersData() as $userData) {
            $user = (new User())
                ->setUsername($userData['username'])
                ->setEmail($userData['email'])
                ->setUpdatedAt($userData['updated_at'])
                ->setHashingAlgorithm($userData['hashing_algorithm'])
                ->setPhone($userData['phone'])
                ->setConfirmed($userData['confirmed'])
                ->setStatus($userData['status'])
            ;
            $user->setPassword($this->hasher->hashPassword($user, $userData['password']));

            if (!empty($userData['roles'])) {
                foreach ($userData['roles'] as $role) {
                    $user->addRole($role);
                }
            }
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUsersData(): array
    {
        $currentDateTime = new \DateTimeImmutable();

        return [
            [
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '123',
                'roles' => [User::ROLE_ADMIN],
                'updated_at' => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone' => '+143900500100',
                'confirmed' => true,
                'status' => User::STATUS_ACTIVE,
            ],
            [
                'username' => 'user',
                'email' => 'user@admin.com',
                'password' => '123',
                'roles' => [],
                'updated_at' => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone' => '+143900500101',
                'confirmed' => true,
                'status' => User::STATUS_ACTIVE,
            ],
            [
                'username' => 'new_user',
                'email' => 'new_user@admin.com',
                'password' => '123',
                'roles' => [],
                'updated_at' => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone' => '+143900500102',
                'confirmed' => false,
                'status' => User::STATUS_NEW,
            ],
            [
                'username' => 'user_blocked',
                'email' => 'user_blocked@admin.com',
                'password' => '123',
                'roles' => [],
                'updated_at' => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone' => '+143900500103',
                'confirmed' => true,
                'status' => User::STATUS_BLOCKED,
            ],
            [
                'username' => 'deleted_user',
                'email' => 'deleted_user@admin.com',
                'password' => '123',
                'roles' => [],
                'updated_at' => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone' => '+143900500104',
                'confirmed' => true,
                'status' => User::STATUS_DELETED,
            ],
            [
                'username' => 'moderator',
                'email' => 'moderator@admin.com',
                'password' => '123',
                'roles' => [User::ROLE_MODERATOR],
                'updated_at' => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone' => '+143900500105',
                'confirmed' => true,
                'status' => User::STATUS_ACTIVE,
            ],
        ];
    }
}
