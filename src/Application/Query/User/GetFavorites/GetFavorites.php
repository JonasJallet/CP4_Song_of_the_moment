<?php

namespace App\Application\Query\User\GetFavorites;

class GetFavorites
{
    public int $user;

    public function __construct(int $user)
    {
        $this->user = $user;
    }
}
