<?php

namespace App\Infrastructure\Service;

use Exception;
use Google\Client as GoogleClient;
use Google\Service\YouTube;
use App\Infrastructure\Persistence\Entity\Song;

class LinkYoutubeCheck
{
    private string $googleApiKeyCheck;
    public function __construct(string $googleApiKeyCheck)
    {
        $this->googleApiKeyCheck = $googleApiKeyCheck;
    }

    public function isValidYouTubeLink(Song $song): bool
    {
        $googleClient = new GoogleClient();
        $googleClient->setDeveloperKey($this->googleApiKeyCheck);
        $youTube = new YouTube($googleClient);

        try {
            $video = $youTube->videos->listVideos('status', ['id' => $song->getLinkYoutube()])->getItems()[0];
            return $video && $video->getStatus()->getUploadStatus() === 'processed';
        } catch (Exception) {
            return false;
        }
    }
}
