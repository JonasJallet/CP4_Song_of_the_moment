<?php

namespace App\Domain\Service;

interface SongDeezerSearchInterface
{
    public function search(string $query): array;
}
