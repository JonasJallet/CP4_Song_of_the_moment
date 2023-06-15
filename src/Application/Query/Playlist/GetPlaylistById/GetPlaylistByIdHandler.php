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
        $id = $getPlaylistById->playlistId;
        return $this->playlistRepository->findOneBy(['id' => $id]);
    }
}
