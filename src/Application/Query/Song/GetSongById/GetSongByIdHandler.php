<?php

namespace App\Application\Query\Song\GetSongById;

use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;

class GetSongByIdHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
    ) {
    }

    public function __invoke(GetSongById $getSongById): DomainSongModelInterface
    {
        $id = $getSongById->songId;

        return $this->songRepository->findOneBy(['id' => $id]);
    }
}
