<?php

namespace App\Tests\FunctionnalTest\Query\User;

use App\Application\Query\User\GetPlaylists\GetPlaylists;
use App\Application\Query\User\GetPlaylists\GetPlaylistsHandler;
use App\Domain\Repository\DomainPlaylistRepositoryInterface;
use App\Domain\Repository\DomainSongRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Playlist;
use App\Infrastructure\Persistence\Entity\User;
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

        $randomSongsPlaylist = ['songA', 'songB'];

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

        $playlistRepository->expects($this->exactly(count($playlists)))
            ->method('randomSongsByPlaylistId')
            ->willReturn($randomSongsPlaylist);

        $handler = new GetPlaylistsHandler(
            $this->createMock(DomainSongRepositoryInterface::class),
            $userRepository,
            $playlistRepository
        );

        $query = new GetPlaylists($userId);
        $result = $handler($query);
        $this->assertIsArray($result);
    }
}
