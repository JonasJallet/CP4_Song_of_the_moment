<?php

namespace App\Domain\Repository;

use App\Domain\Model\DomainPlaylistModelInterface;

/**
 * @method DomainPlaylistModelInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainPlaylistModelInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomainPlaylistModelInterface[]    findAll()
 * @method DomainPlaylistModelInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface DomainPlaylistRepositoryInterface
{
    public function save(DomainPlaylistModelInterface $entity, bool $flush = false): void;

    public function remove(DomainPlaylistModelInterface $entity, bool $flush = false): void;

    public function randomSongsByPlaylistId(string $playlistId): array;
}
