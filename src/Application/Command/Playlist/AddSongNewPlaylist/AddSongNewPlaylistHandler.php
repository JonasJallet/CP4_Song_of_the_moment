<?php

namespace App\Application\Command\Playlist\AddSongNewPlaylist;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;
use App\Domain\Service\PlaylistServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddSongNewPlaylistHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
        public DomainSongRepositoryInterface $songRepository,
        public DomainUserRepositoryInterface $userRepository,
        public PlaylistServiceInterface $playlistService,
    ) {
    }

    public function __invoke(AddSongNewPlaylist $addSongNewPlaylist): JsonResponse
    {
        $song = $this->songRepository->findOneBy(['id' => $addSongNewPlaylist->songId]);
        $user = $this->userRepository->findOneBy(['id' => $addSongNewPlaylist->userId]);
        $playlist = $addSongNewPlaylist->playlist;
        $playlist->setUser($user);
        $message = $this->playlistService->addSong($playlist, $song);
        return new JsonResponse([
            'message' => $message
        ]);
    }
}
