<?php

namespace App\Domain\Repository;

use App\Domain\Model\DomainSongFavoriteModelInterface;
use App\Domain\Model\DomainUserModelInterface;

interface DomainSongFavoriteRepositoryInterface
{
    public function save(DomainSongFavoriteModelInterface $entity, bool $flush = false): void;

    public function remove(DomainSongFavoriteModelInterface $entity, bool $flush = false): void;

    public function findFavoritesUser(DomainUserModelInterface $user): array;
}
