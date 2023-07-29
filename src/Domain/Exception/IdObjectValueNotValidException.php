<?php

namespace App\Domain\Exception;

final class IdObjectValueNotValidException extends DomainException
{
    public function __construct()
    {
        parent::__construct("The id cannot be more than 9999.", 404);
    }
}