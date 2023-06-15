<?php

namespace App\Application\Command\Playlist\NewPlaylistAddSong;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;
use App\Domain\Service\PlaylistServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class NewPlaylistAddSongHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
        public DomainSongRepositoryInterface     $songRepository,
        public DomainUserRepositoryInterface     $userRepository,
        public PlaylistServiceInterface          $playlistService,
    ) {
    }

    public function __invoke(NewPlaylistAddSong $newPlaylistAddSong): JsonResponse
    {
        $songId = $newPlaylistAddSong->songId;
        $userId = $newPlaylistAddSong->userId;
        $song = $this->songRepository->findOneBy(['id' => $songId]);
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $playlist = $newPlaylistAddSong->playlist;
        $playlist->setUser($user);
        $message = $this->playlistService->addSong($playlist, $song);
        return new JsonResponse([
            'message' => $message
        ]);
    }
}
