<?php

namespace App\Domain\Repository;

use App\Domain\Model\DomainPlaylistModelInterface;

/**
 * @method DomainPlaylistModelInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainPlaylistModelInterface[] findAll()
 */
interface DomainPlaylistRepositoryInterface
{
    public function save(DomainPlaylistModelInterface $entity, bool $flush = false): void;

    public function remove(DomainPlaylistModelInterface $entity, bool $flush = false): void;

    public function randomSongsByPlaylistId(string $playlistId): array;

    public function findOneBy(array $criteria, array $orderBy = null): ?DomainPlaylistModelInterface;

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    public function findOneBySlug(string $slug): ?DomainPlaylistModelInterface;

}
