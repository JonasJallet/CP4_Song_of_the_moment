<?php

namespace App\Application\Command\Playlist\AddSongPlaylist;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\PlaylistServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddSongPlaylistHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
        public DomainSongRepositoryInterface     $songRepository,
        public PlaylistServiceInterface          $playlistService,
    ) {
    }

    public function __invoke(AddSongPlaylist $addSongPlaylist): JsonResponse
    {
        $song = $this->songRepository->findOneBy(['id' => $addSongPlaylist->songId]);
        $playlist = $this->playlistRepository->findOneBy(['id' => $addSongPlaylist->playlistId]);
        $message = $this->playlistService->addSong($playlist, $song);
        return new JsonResponse([
            'message' => $message
        ]);
    }
}
