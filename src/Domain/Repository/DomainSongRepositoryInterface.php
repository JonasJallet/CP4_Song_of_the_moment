<?php

namespace App\Domain\Repository;

interface DomainSongRepositoryInterface
{
    public function find(int $id);
    public function findOneBy(array $criteria);
    public function allApprovedSong();
}
