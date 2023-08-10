<?php

namespace App\Tests\FunctionnalTest\Query\Playlist;

use App\Application\Query\Playlist\GetPlaylistBySlug\GetPlaylistBySlug;
use App\Application\Query\Playlist\GetPlaylistBySlug\GetPlaylistBySlugHandler;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Playlist;
use PHPUnit\Framework\TestCase;

class GetPlaylistBySlugTest extends TestCase
{
    private ?DomainPlaylistRepositoryInterface $playlistRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->playlistRepository = $this->createMock(DomainPlaylistRepositoryInterface::class);
    }

    public function testGetSongBySlug()
    {
        $getPlaylistBySlug = new GetPlaylistBySlug();
        $getPlaylistBySlug->slug = 'test';
        $playlist = new Playlist();
        $playlist->setSlug('test');

        $this->playlistRepository->expects($this->once())
            ->method('findOneBySlug')
            ->with($getPlaylistBySlug->slug)
            ->willReturn($playlist);

        $handler = new GetPlaylistBySlugHandler($this->playlistRepository);
        $this->assertEquals($playlist, $handler->__invoke($getPlaylistBySlug));
    }
}
