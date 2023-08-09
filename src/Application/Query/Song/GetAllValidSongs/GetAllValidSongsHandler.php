<?php

namespace App\Application\Query\Song\GetAllValidSongs;

use App\Domain\Repository\DomainSongRepositoryInterface;

class GetAllValidSongsHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $domainSongRepository,
    ) {
    }

    public function __invoke(GetAllValidSongs $getAllValidSongs): array
    {
        return $this->domainSongRepository->allValidSong();
    }
}
