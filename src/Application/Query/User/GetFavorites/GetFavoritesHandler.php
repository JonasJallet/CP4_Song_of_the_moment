<?php

namespace App\Application\Query\User\GetFavorites;

use App\Domain\Repository\DomainSongFavoriteRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;

class GetFavoritesHandler
{
    public function __construct(
        public DomainSongFavoriteRepositoryInterface $favoriteRepository,
        public DomainUserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(GetFavorites $getFavorites): array
    {
        $user = $this->userRepository->findOneBy(['id' => $getFavorites->user]);
        return $this->favoriteRepository->findFavoritesUser($user);
    }
}
