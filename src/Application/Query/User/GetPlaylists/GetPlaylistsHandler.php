<?php

namespace App\Application\Query\User\GetPlaylists;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;

class GetPlaylistsHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public DomainUserRepositoryInterface $userRepository,
        public DomainPlaylistRepositoryInterface $playlistRepository,
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
            $randomSongs = $this->playlistRepository->randomSongsByPlaylistId($playlist->getId());
            $collection[$playlist->getId()] = [
                'playlist' => $playlist,
                'songs' => $randomSongs,
            ];
        }
        return $collection;
    }
}
