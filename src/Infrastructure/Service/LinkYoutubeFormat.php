<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\LinkYoutubeFormatInterface;

class LinkYoutubeFormat implements LinkYoutubeFormatInterface
{
    public function format(string $linkYoutube): string
    {
        return str_replace(
            ['https://www.youtube.com/watch?v=', 'https://youtube.com/watch?v=', 'https://youtu.be/'],
            '',
            $linkYoutube
        );
    }
}
