<?php

namespace App\Tests\ServiceTest;

use App\Infrastructure\Service\LinkYoutubeFormat;
use PHPUnit\Framework\TestCase;

class LinkYoutubeFormatTest extends TestCase
{
    private LinkYoutubeFormat $formatter;

    protected function setUp(): void
    {
        $this->formatter = new LinkYoutubeFormat();
    }

    public function linkYoutubeDataProvider(): array
    {
        return [
            ['https://www.youtube.com/watch?v=VIDEO_ID', 'VIDEO_ID'],
            ['https://youtube.com/watch?v=VIDEO_ID', 'VIDEO_ID'],
            ['https://youtu.be/VIDEO_ID', 'VIDEO_ID'],
            ['VIDEO_ID', 'VIDEO_ID'], // already formatted
            ['https://notyoutube.com/watch?v=VIDEO_ID', 'https://notyoutube.com/watch?v=VIDEO_ID'], // invalid
        ];
    }

    /**
     * @dataProvider linkYoutubeDataProvider
     */
    public function testFormat(string $input, string $expected): void
    {
        $this->assertEquals($expected, $this->formatter->format($input));
    }
}
