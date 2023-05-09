<?php

namespace App\Domain\Service\Manager;

use App\Domain\Model\DomainSongModelInterface;

interface DomainSongManagerInterface
{
    public function formatLinkYoutube(string $linkYoutube);
}
