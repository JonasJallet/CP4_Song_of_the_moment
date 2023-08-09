<?php

namespace App\Tests\FunctionnalTest\Query\Song;

use App\Application\Query\Song\GetAllValidSongs\GetAllValidSongs;
use App\Application\Query\Song\GetAllValidSongs\GetAllValidSongsHandler;
use App\Application\Query\Song\GetSongBySlug\GetSongBySlug;
use App\Application\Query\Song\GetSongBySlug\GetSongBySlugHandler;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Song;
use PHPUnit\Framework\TestCase;

class GetAllValidSongsTest extends TestCase
{
    private ?DomainSongRepositoryInterface $songRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->songRepository = $this->createMock(DomainSongRepositoryInterface::class);
    }

    public function testGetSongBySlug()
    {
        $getAllValidSongs = new GetAllValidSongs();

        $song = new Song();
        $this->songRepository->expects($this->once())
            ->method('allValidSong')
            ->willReturn([$song]);

        $handler = new GetAllValidSongsHandler($this->songRepository);
        $this->assertEquals([$song], $handler->__invoke($getAllValidSongs));
    }
}
