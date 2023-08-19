<?php

namespace App\Infrastructure\Persistence\Fixtures;

use App\Infrastructure\Persistence\Entity\Playlist;
use App\Infrastructure\Persistence\Entity\SongPlaylist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PlaylistFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $playlist = new Playlist();
            $playlist->setName($faker->word());
            $userReference = 'user_' . $faker->numberBetween(0, 1);
            $user = $this->getReference($userReference);
            $playlist->setUser($user);

            for ($j = 0; $j < 10; $j++) {
                $songReference = 'song_' . $faker->numberBetween(0, 23);
                $song = $this->getReference($songReference);
                $songPlaylist = new SongPlaylist();
                $songPlaylist->setSong($song);
                $songPlaylist->setPlaylist($playlist);
                $playlist->addSong($songPlaylist);
            }

            $manager->persist($playlist);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SongFixtures::class,
        ];
    }
}
