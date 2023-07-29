<?php

namespace App\Application\Command\Song\IsApprovedSong;

use App\Domain\Repository\DomainSongRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class IsApprovedSongHandler
{
    public function __construct(
        public DomainSongRepositoryInterface $songRepository,
    ) {
    }
    public function __invoke(IsApprovedSong $isApprovedSong): JsonResponse
    {
        $song = $this->songRepository->findOneBy(['id' => $isApprovedSong->songId]);
        $message = $song->setIsApproved(true);
        return new JsonResponse([
            'message' => $message
        ]);
    }
}
