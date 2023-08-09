<?php

namespace App\Application\Query\User\GetPlaylists;

class GetPlaylists
{
    public int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
