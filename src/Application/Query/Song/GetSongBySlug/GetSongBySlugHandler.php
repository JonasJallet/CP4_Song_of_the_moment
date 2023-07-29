<?php

namespace App\Application\Query\Song\GetSongBySlug;

use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;

class GetSongBySlugHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
    ) {
    }

    public function __invoke(GetSongBySlug $getSongBySlug): DomainSongModelInterface
    {
        return $this->songRepository->findOneBy(['slug' => $getSongBySlug->slug]);
    }
}
