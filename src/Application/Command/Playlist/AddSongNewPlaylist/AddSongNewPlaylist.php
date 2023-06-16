<?php

namespace App\Application\Command\Playlist\AddSongNewPlaylist;

use App\Domain\Model\DomainPlaylistModelInterface;

class AddSongNewPlaylist
{
    public int $songId;
    public int $userId;
    public DomainPlaylistModelInterface $playlist;
}
