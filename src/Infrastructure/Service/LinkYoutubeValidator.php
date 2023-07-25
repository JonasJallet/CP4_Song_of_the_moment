<?php

namespace App\Infrastructure\Service;

use Exception;
use Google\Client;
use Google\Service\YouTube;
use App\Infrastructure\Persistence\Entity\Song;

class LinkYoutubeValidator
{
    private YouTube $youTube;

    public function __construct(string $googleApiKey)
    {
        dd('yo jonas');
        $googleClient = new Client();
        $googleClient->setApplicationName('YouTube Validator');
        $googleClient->setDeveloperKey($googleApiKey);
        $this->youTube = new YouTube($googleClient);
    }

    public function isValidYouTubeLink(Song $song): bool
    {
        try {
            $video = $this->youTube->videos->listVideos('status', ['id' => $song->getLinkYoutube()])->getItems()[0];
            return $video && $video->getStatus()->getUploadStatus() === 'processed';
        } catch (Exception) {
            return false;
        }
    }
}
