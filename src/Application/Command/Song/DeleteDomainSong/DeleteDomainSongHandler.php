<?php

namespace App\Application\Command\Song\DeleteDomainSong;

use App\Domain\Repository\DomainSongRepositoryInterface;

class DeleteDomainSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $domainSongRepository,
    ) {
    }
    public function __invoke(DeleteDomainSong $deleteDomainSong): void
    {
        $id =  $deleteDomainSong->songId;
        $song = $this->domainSongRepository->findOneBy(['id' => $id]);
        $this->domainSongRepository->remove($song, true);
    }
}
