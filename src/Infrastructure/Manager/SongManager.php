<?php

namespace App\Infrastructure\Manager;

use App\Infrastructure\Persistence\Entity\Song;

class SongManager
{
    public function formatLinkYoutube(Song $song): void
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
