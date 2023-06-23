<?php

namespace App\Application\Command\Song\DeleteSong;

use App\Domain\Repository\DomainSongRepositoryInterface;

class DeleteSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
    ) {
    }
    public function __invoke(DeleteSong $deleteSong): void
    {
        $song = $this->songRepository->findOneBy(['id' => $deleteSong->songId]);
        $this->songRepository->remove($song, true);
    }
}
