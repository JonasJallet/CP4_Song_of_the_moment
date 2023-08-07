<?php

namespace App\Domain\Service;

interface LinkYoutubeSearchInterface
{
    public function search(string $songArtist, string $songTitle): string;
}
