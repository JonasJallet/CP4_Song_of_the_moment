<?php

namespace App\Application\Command\SearchApi\CreateSong;

class CreateSong
{
    private array $songsData;

    public function __construct(array $songsData)
    {
        $this->songsData = $songsData;
    }

    public function getSongsData(): array
    {
        return $this->songsData;
    }
}
