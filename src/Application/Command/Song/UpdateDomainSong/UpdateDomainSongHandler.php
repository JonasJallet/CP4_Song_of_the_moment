<?php

namespace App\Application\Command\Song\UpdateDomainSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\SongServiceInterface;

class UpdateDomainSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $domainSongRepository,
        public SongServiceInterface          $songService,
    ) {
    }
    public function __invoke(UpdateDomainSong $updateDomainSong): void
    {
        $song = $this->domainSongRepository->findOneBy(['id' => $updateDomainSong->id]);
        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->songService->formatLinkYoutube($linkYoutube);
        $song->setLinkYoutube($linkFormat);
        $this->domainSongRepository->save($song, true);
    }
}
