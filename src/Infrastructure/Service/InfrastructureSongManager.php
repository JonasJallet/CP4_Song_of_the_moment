<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\Manager\DomainSongManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class InfrastructureSongManager implements DomainSongManagerInterface
{
//    private EntityManagerInterface;
//    public function __construct(
//        private EntityManagerInterface $entityManager
//    ) {
//    }
    public function formatLinkYoutube(string $linkYoutube): void
    {
        $linkYoutube = $song->getLinkYoutube();
        $linkReplace = str_replace(
            ['https://www.youtube.com/watch?v=', 'https://youtube.com/watch?v=', 'https://youtu.be/'],
            '',
            $linkYoutube
        );
        $song->setLinkYoutube($linkReplace);
    }
}
