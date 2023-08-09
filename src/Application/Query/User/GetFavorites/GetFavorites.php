<?php

namespace App\Application\Query\User\GetFavorites;

class GetFavorites
{
    public int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}
