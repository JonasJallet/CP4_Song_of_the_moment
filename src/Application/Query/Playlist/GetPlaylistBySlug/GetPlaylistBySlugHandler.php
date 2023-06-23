<?php

namespace App\Application\Query\Playlist\GetPlaylistBySlug;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;

class GetPlaylistBySlugHandler
{
    public function __construct(
        public DomainPlaylistRepositoryInterface $playlistRepository,
    ) {
    }

    public function __invoke(GetPlaylistBySlug $getPlaylistBySlug): DomainPlaylistModelInterface
    {
        return $this->playlistRepository->findOneBy(['slug' => $getPlaylistBySlug->slug]);
    }
}
