<?php

namespace App\Application\Command\Playlist\NewPlaylistAddSong;

use App\Domain\Model\DomainPlaylistModelInterface;

class NewPlaylistAddSong
{
    public int $songId;
    public int $userId;
    public DomainPlaylistModelInterface $playlist;
}
