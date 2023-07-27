<?php

namespace App\Infrastructure\Validator;

use App\Infrastructure\Persistence\Entity\Song;
use App\Infrastructure\Persistence\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SongValidator extends ConstraintValidator
{
    public function __construct(
        private readonly SongRepository $songRepository
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        $this->ensureUniquePairingSongName($value);
    }

    private function ensureUniquePairingSongName(Song $song): void
    {
        $errors = new ArrayCollection();

        if ($song->getTitle() !== null && $song->getArtist() !== null) {
            $existingSongPairing = $this->songRepository
                ->findBy([
                    'title' => $song->getTitle(),
                    'artist' => $song->getArtist(),
                ]);

            if (sizeof($existingSongPairing) > 1) {
                $errors->add(['message' => 'Le titre existe déjà pour l\'artiste indiqué.']);
            }

            if ($song->getId() !== null) {
                foreach ($existingSongPairing as $existingSong) {
                    if ($existingSong->getId() != $song->getId()) {
                        $errors->add(['message' => 'Le titre existe déjà pour l\'artiste indiqué.']);
                    }
                }
            }

            foreach ($errors as $error) {
                $this->context->buildViolation($error['message'])
                    ->atPath('title')
                    ->addViolation();
            }
        }
    }
}
