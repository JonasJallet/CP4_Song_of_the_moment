<?php

namespace App\Application\Query\SearchApi\GetSongsData;

use App\Domain\Service\SongDeezerSearchInterface;

class GetSongsDataHandler
{
    public function __construct(
        public SongDeezerSearchInterface $songDeezerSearch
    ) {
    }

    public function __invoke(GetSongsData $getSongsData): array
    {
        $songTitle = $getSongsData->getSongTitle();
        return $this->songDeezerSearch->search($songTitle);
    }
}
