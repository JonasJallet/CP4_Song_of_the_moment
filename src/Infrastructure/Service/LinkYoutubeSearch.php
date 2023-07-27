<?php

namespace App\Infrastructure\Service;

use Google\Client as GoogleClient;
use Google\Service\YouTube;

class LinkYoutubeSearch
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
        $searchQuery = $songArtist . ' ' . $songTitle;
        $youtubeSearch = $youtube->search->listSearch(
            'snippet',
            ['q' => $searchQuery,
                'maxResults' => 1,
                'order' => 'viewCount']
        );
        return $youtubeSearch['items'][0]['id']['videoId'];
    }
}
