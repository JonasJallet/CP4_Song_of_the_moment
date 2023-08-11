<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\LinkYoutubeSearchInterface;
use Google\Client as GoogleClient;
use Google\Service\YouTube;

class LinkYoutubeSearch implements LinkYoutubeSearchInterface
{
    private string $googleApiKeySearch;

    public function __construct(string $googleApiKeySearch)
    {
        $this->googleApiKeySearch = $googleApiKeySearch;
    }
    public function search(string $songArtist, string $songTitle): string
    {
        $googleClient = new GoogleClient();
        $googleClient->setDeveloperKey($this->googleApiKeySearch);
        $youtube = new YouTube($googleClient);
        $searchQuery = $songArtist . ' - ' . $songTitle;
        $youtubeSearch = $youtube->search->listSearch(
            'id',
            [
            'q' => $searchQuery,
                'maxResults' => 1
            ]
        );
        return $youtubeSearch['items'][0]['id']['videoId'];
    }
}
