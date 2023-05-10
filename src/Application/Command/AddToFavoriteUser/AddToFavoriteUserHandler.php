<?php

namespace App\Application\Command\AddToFavoriteUser;

use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;

class AddToFavoriteUserHandler
{
    public function __construct(
        public DomainUserRepositoryInterface $domainUserRepository,
        public DomainSongRepositoryInterface $domainSongRepository,
        public DomainSongModelInterface $domainSongModel,
    ) {
    }
    public function __invoke(AddToFavoriteUser $addToFavoriteUser): bool
    {
        $userId = $addToFavoriteUser->userId;
        $songId = $addToFavoriteUser->songId;

        $user = $this->domainUserRepository->findOneBy(['id' => $userId]);
        $song = $this->domainSongRepository->findOneBy(['id' => $songId]);

        if ($user->isInFavorite($song)) {
            $user->removeFavorite($song);
        } else {
            $user->addFavorite($song);
        }
        $this->domainUserRepository->save($user, true);

        return $user->isInFavorite($song);
    }
}
