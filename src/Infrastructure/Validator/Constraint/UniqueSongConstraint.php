<?php

namespace App\Infrastructure\Validator\Constraint;

use App\Infrastructure\Validator\UniqueSongValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueSongConstraint extends Constraint
{
    public string $message = 'Le son est déjà dans la playlist';

    public function validatedBy(): string
    {
        return UniqueSongValidator::class;
    }
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
