<?php

namespace App\Tests\FunctionnalTest\Query\SearchApi;

use App\Application\Query\SearchApi\GetSongsData\GetSongsData;
use App\Application\Query\SearchApi\GetSongsData\GetSongsDataHandler;
use App\Domain\Service\SongDeezerSearchInterface;
use PHPUnit\Framework\TestCase;

class GetSongsDataTest extends TestCase
{
    public function testGetSongsDataTest()
    {
        $mockSongDeezerSearch = $this->createMock(SongDeezerSearchInterface::class);
        $mockSongDeezerSearch->expects($this->once())
            ->method('search')
            ->with('title')
            ->willReturn([
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'title',
                        'artist' => [
                            'name' => 'test',
                        ],
                        'album' => [
                            'title' => 'test',
                            'cover_medium' => 'test',
                        ],
                        'preview' => 'test',
                    ],
                ],
            ]);

        $handler = new GetSongsDataHandler($mockSongDeezerSearch);
        $query = new GetSongsData('title');
        $result = $handler($query);
        $this->assertIsArray($result);
        $this->assertEquals('title', $result['data'][0]['title']);
    }
}
