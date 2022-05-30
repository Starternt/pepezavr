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
        foreach ($this->getUsersData() as $userData) {
            $user = (new User())
                ->setUsername($userData['username'])
                ->setEmail($userData['email'])
                ->setName($userData['name'])
                ->setUpdatedAt($userData['updated_at'])
                ->setHashingAlgorithm($userData['hashing_algorithm'])
                ->setPhone($userData['phone'])
                ->setConfirmed($userData['confirmed'])
                ->setStatus($userData['status']);
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
                'username'          => 'admin',
                'email'             => 'admin@admin.com',
                'password'          => '123',
                'name'              => 'The boss',
                'roles'             => ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
                'updated_at'        => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone'             => '+143900500100',
                'confirmed'         => true,
                'status'            => User::STATUS_ACTIVE,
            ],
            [
                'username'          => 'user',
                'email'             => 'user@admin.com',
                'password'          => '123',
                'name'              => 'John Wick',
                'roles'             => [],
                'updated_at'        => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone'             => '+143900500101',
                'confirmed'         => true,
                'status'            => User::STATUS_ACTIVE,
            ],
            [
                'username'          => 'new_user',
                'email'             => 'new_user@admin.com',
                'password'          => '123',
                'name'              => 'Pepe the frog',
                'roles'             => [],
                'updated_at'        => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone'             => '+143900500102',
                'confirmed'         => false,
                'status'            => User::STATUS_NEW,
            ],
            [
                'username'          => 'user_blocked',
                'email'             => 'user_blocked@admin.com',
                'password'          => '123',
                'name'              => 'Leonardo DiCaprio',
                'roles'             => [],
                'updated_at'        => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone'             => '+143900500103',
                'confirmed'         => true,
                'status'            => User::STATUS_BLOCKED,
            ],
            [
                'username'          => 'deleted_user',
                'email'             => 'deleted_user@admin.com',
                'password'          => '123',
                'name'              => 'Not important',
                'roles'             => [],
                'updated_at'        => $currentDateTime,
                'hashing_algorithm' => User::HASHING_ALGORITHM_ARGON2I,
                'phone'             => '+143900500104',
                'confirmed'         => true,
                'status'            => User::STATUS_DELETED,
            ],
        ];
    }
}
