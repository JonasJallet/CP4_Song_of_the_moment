<?php

namespace App\Application\Command\Playlist\DeleteSongPlaylist;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteSongPlaylistHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public DomainPlaylistRepositoryInterface $playlistRepository,
    ) {
    }

    public function __invoke(DeleteSongPlaylist $deleteSongPlaylist): JsonResponse
    {
        $song = $this->songRepository->findOneBy(['id' => $deleteSongPlaylist->songId]);
        $playlist = $this->playlistRepository->findOneBy(['id' => $deleteSongPlaylist->playlistId]);
        $message = $playlist->removeSong($song);
        return new JsonResponse([
            'message' => $message
        ]);
    }
}
