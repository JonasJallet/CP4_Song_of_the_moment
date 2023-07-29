<?php

namespace App\Application\Command\Song\NewSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\LinkYoutubeFormatInterface;

class NewSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public LinkYoutubeFormatInterface $linkYoutubeFormat,
    ) {
    }
    public function __invoke(NewSong $newSong): void
    {
        $song = $newSong->song;
        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->linkYoutubeFormat->format($linkYoutube);
        $song->setLinkYoutube($linkFormat);
        $this->songRepository->save($song, true);
    }
}
