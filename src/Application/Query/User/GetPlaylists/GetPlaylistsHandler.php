<?php

namespace App\Application\Query\User\GetPlaylists;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;

class GetPlaylistsHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public DomainUserRepositoryInterface $userRepository,
        public DomainPlaylistRepositoryInterface $playlistRepository,
        public DomainSongPlaylistRepositoryInterface $songPlaylistRepo
    ) {
    }

    public function __invoke(GetPlaylists $getPlaylists): array
    {
        $user = $this->userRepository->findOneBy(
            ['id' => $getPlaylists->userId]
        );
        $playlistsByUser = $this->playlistRepository->findBy(
            ['user' => $user],
            ['name' => 'ASC']
        );
        $collection = [];
        foreach ($playlistsByUser as $playlist) {
            $songPlaylists = $this->songPlaylistRepo->findBy(['playlist' => $playlist]);
            $randomSongs = [];
            foreach ($songPlaylists as $songPlaylist) {
                $randomSongs[] = $songPlaylist->getSong();
            }

            $collection[$playlist->getId()] = [
                'playlist' => $playlist,
                'songs' => $randomSongs,
            ];
        }
        return $collection;
    }
}

