<?php

namespace App\Application\Command\Song\AddToFavoriteSong;

use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;

class AddToFavoriteSongHandler
{
    public function __construct(
        public DomainUserRepositoryInterface $domainUserRepository,
        public DomainSongRepositoryInterface $domainSongRepository,
        public DomainSongModelInterface $domainSongModel,
    ) {
    }
    public function __invoke(AddToFavoriteSong $addToFavoriteUser): bool
    {
        $user = $this->domainUserRepository->findOneBy(['id' => $addToFavoriteUser->userId]);
        $song = $this->domainSongRepository->findOneBy(['id' => $addToFavoriteUser->songId]);

        if ($user->isInFavorite($song)) {
            $user->removeFavorite($song);
        } else {
            $user->addFavorite($song);
        }
        $this->domainUserRepository->save($user, true);

        return $user->isInFavorite($song);
    }
}
