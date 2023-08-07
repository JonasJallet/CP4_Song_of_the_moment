<?php

namespace App\Domain\Service;

interface SongUploadCoverInterface
{
    public function upload($url, $name): string;
}
