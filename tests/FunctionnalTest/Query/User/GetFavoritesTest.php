<?php

namespace App\Tests\FunctionnalTest\Query\User;

use App\Application\Query\User\GetFavorites\GetFavorites;
use App\Application\Query\User\GetFavorites\GetFavoritesHandler;
use App\Domain\Repository\DomainSongFavoriteRepositoryInterface;
use App\Domain\Repository\DomainUserRepositoryInterface;
use App\Infrastructure\Persistence\Entity\User;
use PHPUnit\Framework\TestCase;

class GetFavoritesTest extends TestCase
{
    public function testGetFavoritesTest(): void
    {
        $userId = 12;
        $favorites = ['song1', 'song2', 'song3'];

        $userMock = $this->createMock(User::class);

        $userRepository = $this->createMock(DomainUserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $userId])
            ->willReturn($userMock);

        $favoriteRepository = $this->createMock(DomainSongFavoriteRepositoryInterface::class);
        $favoriteRepository->expects($this->once())
            ->method('findFavoritesUser')
            ->with($userMock)
            ->willReturn($favorites);

        $handler = new GetFavoritesHandler($favoriteRepository, $userRepository);
        $query = new GetFavorites($userId);
        $result = $handler($query);

        $this->assertSame($favorites, $result);
    }
}

