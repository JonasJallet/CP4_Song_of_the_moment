<?php

namespace App\Domain\Repository;

interface DomainSongRepositoryInterface
{
    public function find(int $id);
}
