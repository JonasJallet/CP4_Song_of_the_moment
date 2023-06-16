<?php

namespace App\Application\Query\Playlist\GetPlaylistById;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;

class GetPlaylistByIdHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
    ) {
    }

    public function __invoke(GetPlaylistById $getPlaylistById): DomainPlaylistModelInterface
    {
        return $this->playlistRepository->findOneBy(['id' => $getPlaylistById->playlistId]);
    }
}
