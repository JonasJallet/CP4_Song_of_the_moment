<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'username' => 'admin',
                'password' => 'admin',
                'role' => 'ROLE_ADMIN',
            ],
            [
                'username' => 'spirit',
                'password' => 'spirit',
                'role' => 'ROLE_USER',
            ]
        ];

        foreach ($users as $key => $user) {
            $newUser = new User();
            $newUser->setUsername($user['username']);
            $hash = $this->passwordHasher->hashPassword($newUser, $user['password']);
            $newUser->setPassword($hash);
            $newUser->setRoles([$user['role']]);
            $manager->persist($newUser);
            $this->addReference('user_' . $key, $newUser);
        }
        $manager->flush();
    }
}
