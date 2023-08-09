<?php

namespace App\Domain\Repository;

use App\Domain\Model\DomainSongModelInterface;

/**
 * @method DomainSongModelInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainSongModelInterface[]    findAll()
 * @method DomainSongModelInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface DomainSongRepositoryInterface
{
    public function allValidSong();

    public function save(DomainSongModelInterface $entity, bool $flush = false): void;

    public function remove(DomainSongModelInterface $entity, bool $flush = false): void;

    public function findOneBy(array $criteria, array $orderBy = null): ?DomainSongModelInterface;
}
