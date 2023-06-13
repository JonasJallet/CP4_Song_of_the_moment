<?php

namespace App\Application\Command\Song\UpdateDomainSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\SongManagerInterface;

class UpdateDomainSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $domainSongRepository,
        public SongManagerInterface $songManager,
    ) {
    }
    public function __invoke(UpdateDomainSong $updateDomainSong): void
    {
        $id =  $updateDomainSong->id;
        $song = $this->domainSongRepository->findOneBy(['id' => $id]);

        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->songManager->formatLinkYoutube($linkYoutube);
        $song->setLinkYoutube($linkFormat);

        $this->domainSongRepository->save($song, true);
    }
}
