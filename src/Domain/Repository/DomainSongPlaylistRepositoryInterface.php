<?php

namespace App\Domain\Repository;

use App\Domain\Model\DomainSongPlaylistModelInterface;
use App\Infrastructure\Persistence\Entity\SongPlaylist;
use App\Infrastructure\Persistence\Entity\Playlist;

interface DomainSongPlaylistRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    public function findOneBy(array $criteria): ?SongPlaylist;

    public function findAll(): array;

    public function save(DomainSongPlaylistModelInterface $entity): void;

    public function remove(DomainSongPlaylistModelInterface $entity): void;
}
