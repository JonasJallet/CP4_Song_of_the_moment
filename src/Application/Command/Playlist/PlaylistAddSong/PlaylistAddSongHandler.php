<?php

namespace App\Application\Command\Playlist\PlaylistAddSong;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\PlaylistServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlaylistAddSongHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
        public DomainSongRepositoryInterface     $songRepository,
        public PlaylistServiceInterface          $playlistService,
    ) {
    }

    public function __invoke(PlaylistAddSong $playlistAddSong): JsonResponse
    {
        $songId = $playlistAddSong->songId;
        $playlistId = $playlistAddSong->playlistId;
        $song = $this->songRepository->findOneBy(['id' => $songId]);
        $playlist = $this->playlistRepository->findOneBy(['id' => $playlistId]);
        $message = $this->playlistService->addSong($playlist, $song);
        return new JsonResponse([
            'message' => $message
        ]);
    }
}
