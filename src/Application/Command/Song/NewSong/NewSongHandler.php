<?php

namespace App\Application\Command\Song\NewSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\SongServiceInterface;

class NewSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public SongServiceInterface          $songService,
    ) {
    }
    public function __invoke(NewSong $newSong): void
    {
        $song = $newSong->song;
        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->songService->formatLinkYoutube($linkYoutube);
        $song->setLinkYoutube($linkFormat);
        $this->songRepository->save($song, true);
    }
}
