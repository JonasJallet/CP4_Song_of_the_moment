<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\SongServiceInterface;
use Google\Client as GoogleClient;
use Google\Service\YouTube;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SongService implements SongServiceInterface
{
    public function __construct(
        private readonly string $googleApiKey
    ) {
    }

    public function formatLinkYoutube(string $linkYoutube): string
    {
        return str_replace(
            ['https://www.youtube.com/watch?v=', 'https://youtube.com/watch?v=', 'https://youtu.be/'],
            '',
            $linkYoutube
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function searchDeezerSongs(string $query): array
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', "https://api.deezer.com/search", [
            'query' => [
                'q' => "track:" . '"' . $query . '"',
                'limit' => '24',
            ],
        ]);

        return $response->toArray()['data'];
    }

    public function searchYoutubeLink(string $songArtist, string $songTitle): string
    {
        $youtubeClient = new GoogleClient();
        $youtubeClient->setDeveloperKey($this->googleApiKey);
        $youtube = new YouTube($youtubeClient);


        $searchQuery = $songArtist . ' ' . $songTitle;
        $youtubeSearch = $youtube->search->listSearch('snippet', ['q' => $searchQuery, 'maxResults' => 1]);
        return $youtubeSearch['items'][0]['id']['videoId'];
    }
}
