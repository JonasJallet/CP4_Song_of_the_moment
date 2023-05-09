<?php

namespace App\Application\Query\GetAllApprovedSongs;

use App\Domain\Model\DomainSongModelInterface;
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
