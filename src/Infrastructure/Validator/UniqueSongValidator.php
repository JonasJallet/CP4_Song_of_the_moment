<?php

namespace App\Infrastructure\Validator;

use App\Infrastructure\Persistence\Entity\Playlist;
use App\Infrastructure\Validator\Constraint\UniqueSongConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSongValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueSongConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueSongConstraint::class);
        }

        if (!$value instanceof Playlist) {
            return;
        }

        foreach ($value->getSongs() as $song) {
            if ($song === $value->getSongs()) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                return;
            }
        }
    }
}
