<?php

namespace App\Tests\FunctionnalTest\Query\User;

use App\Application\Query\User\GetPlaylists\GetPlaylists;
use App\Application\Query\User\GetPlaylists\GetPlaylistsHandler;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Playlist;
use App\Infrastructure\Persistence\Entity\SongPlaylist;
use App\Infrastructure\Persistence\Entity\User;
use App\Infrastructure\Persistence\Entity\Song;
use PHPUnit\Framework\TestCase;

class GetPlaylistsTest extends TestCase
{
    public function testGetPlaylistsTest(): void
    {
        $userId = 12;

        $playlistMock1 = $this->createMock(Playlist::class);
        $playlistMock1->method('getId')->willReturn("Playlist 1");

        $playlistMock2 = $this->createMock(Playlist::class);
        $playlistMock2->method('getId')->willReturn("Playlist 2");

        $playlists = [$playlistMock1, $playlistMock2];

        $songA = $this->createMock(Song::class);
        $songB = $this->createMock(Song::class);

        $songPlaylistA = $this->createMock(SongPlaylist::class);
        $songPlaylistA->method('getSong')->willReturn($songA);

        $songPlaylistB = $this->createMock(SongPlaylist::class);
        $songPlaylistB->method('getSong')->willReturn($songB);

        $userMock = $this->createMock(User::class);

        $userRepository = $this->createMock(DomainUserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $userId])
            ->willReturn($userMock);

        $playlistRepository = $this->createMock(DomainPlaylistRepositoryInterface::class);
        $playlistRepository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $userMock], ['name' => 'ASC'])
            ->willReturn($playlists);

        $songPlaylistRepo = $this->createMock(DomainSongPlaylistRepositoryInterface::class);
        $songPlaylistRepo->expects($this->exactly(count($playlists)))
            ->method('findBy')
            ->willReturnOnConsecutiveCalls([$songPlaylistA], [$songPlaylistB]);

        $handler = new GetPlaylistsHandler(
            $this->createMock(DomainSongRepositoryInterface::class),
            $userRepository,
            $playlistRepository,
            $songPlaylistRepo
        );

        $query = new GetPlaylists($userId);
        $result = $handler($query);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }
}
