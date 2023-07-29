<?php

namespace App\Application\Query\Song\GetAllApprovedSongs;

use App\Domain\Repository\DomainSongRepositoryInterface;

class GetAllApprovedSongsHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $domainSongRepository,
    ) {
    }

    public function __invoke(GetAllApprovedSongs $getAllApprovedSongs): array
    {
        return $this->domainSongRepository->allApprovedSong();
    }
}
