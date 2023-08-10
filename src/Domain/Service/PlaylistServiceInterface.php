<?php

namespace App\Domain\Service;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Model\DomainSongPlaylistModelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

interface PlaylistServiceInterface
{
    public function addSong(
        DomainPlaylistModelInterface $playlist,
        DomainSongPlaylistModelInterface $songPlaylist
    ): JsonResponse;
}
