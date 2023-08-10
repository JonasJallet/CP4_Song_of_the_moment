<?php

namespace App\Infrastructure\Persistence\Fixtures;

use App\Infrastructure\Persistence\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = [
            [
                'email' => 'admin@mail.com',
                'password' => 'admin',
                'role' => 'ROLE_ADMIN',
            ],
            [
                'email' => 'spirit@mail.com',
                'password' => 'spirit',
                'role' => 'ROLE_USER',
            ]
        ];

        foreach ($users as $key => $user) {
            $newUser = new User();

            for ($i = 0; $i < 10; $i++) {
                $songReference = 'song_' . $faker->numberBetween(0, 23);
                $song = $this->getReference($songReference);
                $newUser->addSongFavorite($song);
            }

            $newUser->setUsername($user['email']);
            $hash = $this->passwordHasher->hashPassword($newUser, $user['password']);
            $newUser->setPassword($hash);
            $newUser->setRoles([$user['role']]);
            $manager->persist($newUser);
            $this->addReference('user_' . $key, $newUser);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SongFixtures::class,
        ];
    }
}
