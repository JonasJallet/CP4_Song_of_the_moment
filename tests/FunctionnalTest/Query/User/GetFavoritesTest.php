<?php

namespace App\Tests\FunctionnalTest\Query\User;

use App\Application\Query\User\GetFavorites\GetFavorites;
use App\Application\Query\User\GetFavorites\GetFavoritesHandler;
use App\Domain\Repository\DomainUserRepositoryInterface;
use App\Infrastructure\Persistence\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class GetFavoritesTest extends TestCase
{
    public function testGetFavoritesTest(): void
    {
        $userId = 12;
        $favorites = new ArrayCollection(['song1', 'song2', 'song3']);

        $userMock = $this->createMock(User::class);
        $userMock->expects($this->once())
            ->method('getFavorites')
            ->willReturn($favorites);

        $userRepository = $this->createMock(DomainUserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $userId])
            ->willReturn($userMock);

        $handler = new GetFavoritesHandler($userRepository);
        $query = new GetFavorites($userId);
        $result = $handler($query);

        $this->assertSame($favorites, $result);
    }
}
