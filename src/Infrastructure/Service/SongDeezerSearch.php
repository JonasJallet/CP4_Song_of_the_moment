<?php

namespace App\Infrastructure\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SongDeezerSearch
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function search(string $query): array
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', "https://api.deezer.com/search", [
            'query' => [
                'q' => "track:" . '"' . $query . '"',
                'limit' => '30',
            ],
        ]);

        return $response->toArray()['data'];
    }
}
