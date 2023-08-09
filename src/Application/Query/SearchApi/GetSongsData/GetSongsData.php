<?php

namespace App\Application\Query\SearchApi\GetSongsData;

class GetSongsData
{
    private string $songTitle;

    public function __construct(string $songTitle)
    {
        $this->songTitle = $songTitle;
    }

    public function getSongTitle(): string
    {
        return $this->songTitle;
    }
}

