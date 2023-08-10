<?php

namespace App\Application\Command\Song\AddToFavoriteSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;
use App\Infrastructure\Persistence\Entity\SongFavorite;

class AddToFavoriteSongHandler
{
    public function __construct(
        public DomainUserRepositoryInterface $domainUserRepository,
        public DomainSongRepositoryInterface $domainSongRepository,
    ) {
    }
    public function __invoke(AddToFavoriteSong $addToFavoriteUser): bool
    {
        $user = $this->domainUserRepository->findOneBy(['id' => $addToFavoriteUser->userId]);
        $song = $this->domainSongRepository->findOneBy(['id' => $addToFavoriteUser->songId]);

        if ($user->isInFavorite($song)) {
            foreach ($user->getSongFavorites() as $songFavorite) {
                if ($songFavorite->getSong()->getId() === $song->getId()) {
                    $user->removeSongFavorite($songFavorite);
                }
            }
        } else {
            $songFavorite = new SongFavorite();
            $songFavorite->setUser($user);
            $songFavorite->setSong($song);
            $user->addSongFavorite($songFavorite);
        }

        $this->domainUserRepository->save($user, true);

        return $user->isInFavorite($song);
    }
}
