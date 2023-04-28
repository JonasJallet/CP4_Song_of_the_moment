<?php

namespace App\Domain\Exception;

final class DomainObjectNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct("The domain object was not found.", 404);
    }
}