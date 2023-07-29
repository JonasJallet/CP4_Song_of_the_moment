<?php

namespace App\Infrastructure\Validator\Constraint;

use App\Infrastructure\Validator\SongValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute] class SongConstraint extends Constraint
{
    public function validatedBy(): string
    {
        return SongValidator::class;
    }
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
