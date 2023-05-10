<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\DomainSongManagerInterface;

class InfrastructureSongManager implements DomainSongManagerInterface
{
    public function formatLinkYoutube(string $linkYoutube): string
    {
        return str_replace(
            ['https://www.youtube.com/watch?v=', 'https://youtube.com/watch?v=', 'https://youtu.be/'],
            '',
            $linkYoutube
        );
    }
}
