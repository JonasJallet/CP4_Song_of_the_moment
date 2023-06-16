<?php

namespace App\Application\Command\Playlist\DeletePlaylist;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;

class DeletePlaylistHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
    ) {
    }

    public function __invoke(DeletePlaylist $deletePlaylist): void
    {
        $playlist = $this->playlistRepository->findOneBy(['id' => $deletePlaylist->playlistId]);
        $this->playlistRepository->remove($playlist, true);
    }
}
