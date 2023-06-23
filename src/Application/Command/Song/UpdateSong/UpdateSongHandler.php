<?php

namespace App\Application\Command\Song\UpdateSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Service\SongServiceInterface;

class UpdateSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
        public SongServiceInterface          $songService,
    ) {
    }
    public function __invoke(UpdateSong $updateSong): void
    {
        $song = $this->songRepository->findOneBy(['id' => $updateSong->id]);
        $linkYoutube = $song->getLinkYoutube();
        $linkFormat = $this->songService->formatLinkYoutube($linkYoutube);
        $song->setLinkYoutube($linkFormat);
        $this->songRepository->save($song, true);
    }
}
