<?php

namespace App\Application\Query\Playlist\GetPlaylistBySlug;

use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use RuntimeException;

class GetPlaylistBySlugHandler
{
    private DomainPlaylistRepositoryInterface $playlistRepository;

    public function __construct(DomainPlaylistRepositoryInterface $playlistRepository)
    {
        $this->playlistRepository = $playlistRepository;
    }

    public function __invoke(GetPlaylistBySlug $getPlaylistBySlug)
    {
        $slug = $getPlaylistBySlug->slug;
        $playlist = $this->playlistRepository->findOneBySlug($slug);
        if (!$playlist) {
            throw new RuntimeException("No playlist found for the given slug: " . $slug);
        }
        return $playlist;
    }
}
