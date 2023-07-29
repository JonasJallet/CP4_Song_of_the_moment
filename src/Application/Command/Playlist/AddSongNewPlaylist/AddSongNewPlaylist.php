<?php

namespace App\Application\Command\Playlist\AddSongNewPlaylist;

use App\Domain\Model\DomainPlaylistModelInterface;

class AddSongNewPlaylist
{
    public string $songId;
    public int $userId;
    public DomainPlaylistModelInterface $playlist;
}
