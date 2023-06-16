<?php

namespace App\Application\Query\User\GetFavorites;

use App\Domain\Repository\DomainUserRepositoryInterface;
use Doctrine\Common\Collections\Collection;

class GetFavoritesHandler
{
    public function __construct(
        public DomainUserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(GetFavorites $getFavorites): Collection
    {
        $userFavorite = $this->userRepository->findOneBy(
            ['id' => $getFavorites->userId]
        );

        return $userFavorite->getFavorites();
    }
}
