<?php

namespace App\Application\Command\Song\NewDomainSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\DomainSongManagerInterface;

class NewDomainSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $domainSongRepository,
        public DomainSongManagerInterface $domainSongManager,
    ) {
    }
    public function __invoke(NewDomainSong $newDomainSong): void
    {
        $song = $newDomainSong->song;

        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->domainSongManager->formatLinkYoutube($linkYoutube);
        $song->setLinkYoutube($linkFormat);

        $this->domainSongRepository->save($song, true);
    }
}
