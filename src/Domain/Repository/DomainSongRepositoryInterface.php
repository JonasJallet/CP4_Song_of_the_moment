<?php

namespace App\Domain\Repository;

use App\Domain\Model\DomainSongModelInterface;
use App\Infrastructure\Persistence\Entity\Song;

interface DomainSongRepositoryInterface
{
    public function find(int $id);
    public function findOneBy(array $criteria);
    public function allApprovedSong();

    public function save(DomainSongModelInterface $entity, bool $flush = false): void;
};
