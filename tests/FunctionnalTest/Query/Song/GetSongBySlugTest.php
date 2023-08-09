<?php

namespace App\Tests\FunctionnalTest\Query\Song;

use App\Application\Query\Song\GetSongBySlug\GetSongBySlug;
use App\Application\Query\Song\GetSongBySlug\GetSongBySlugHandler;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Song;
use PHPUnit\Framework\TestCase;

class GetSongBySlugTest extends TestCase
{
    private ?DomainSongRepositoryInterface $songRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->songRepository = $this->createMock(DomainSongRepositoryInterface::class);
    }

    public function testGetSongBySlug()
    {
        $getSongBySlug = new GetSongBySlug();
        $getSongBySlug->slug = 'test';

        $song = new Song();
        $song->setSlug('test');

        $this->songRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['slug' => $getSongBySlug->slug])
            ->willReturn($song);

        $handler = new GetSongBySlugHandler($this->songRepository);
        $this->assertEquals($song, $handler->__invoke($getSongBySlug));
    }
}
