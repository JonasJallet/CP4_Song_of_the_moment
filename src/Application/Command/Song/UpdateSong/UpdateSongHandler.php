<?php

namespace App\Application\Command\Song\UpdateSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\LinkYoutubeFormatInterface;

class UpdateSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public LinkYoutubeFormatInterface $linkYoutubeFormat,
    ) {
    }
    public function __invoke(UpdateSong $updateSong): void
    {
        $song = $this->songRepository->findOneBy(['id' => $updateSong->id]);
        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->linkYoutubeFormat->format($linkYoutube);
        $song->setLinkYoutube($linkFormat);
        $song->setLinkYoutubeValid(true);
        $this->songRepository->save($song, true);
    }
}
