<?php

namespace App\Application\Command\Playlist\AddSongPlaylist;

use App\Domain\Model\DomainSongPlaylistModelInterface;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\PlaylistServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddSongPlaylistHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
        public DomainSongRepositoryInterface $songRepository,
        public DomainSongPlaylistRepositoryInterface $songPlaylistRepo,
        public PlaylistServiceInterface $playlistService,
        public DomainSongPlaylistModelInterface $songPlaylist,
    ) {
    }

    public function __invoke(AddSongPlaylist $addSongPlaylist): JsonResponse
    {
        $song = $this->songRepository->findOneBy(['id' => $addSongPlaylist->songId]);
        $playlist = $this->playlistRepository->findOneBy(['id' => $addSongPlaylist->playlistId]);

        $songPlaylist = $this->songPlaylistRepo->findOneBy([
            'song' => $song,
            'playlist' => $playlist
        ]);

        if (!$songPlaylist) {
            $songPlaylist = new $this->songPlaylist();
            $songPlaylist->setSong($song);
            $songPlaylist->setPlaylist($playlist);
        }

        $message = $this->playlistService->addSong($playlist, $songPlaylist);
        return new JsonResponse([
            'message' => $message
        ]);
    }
}
