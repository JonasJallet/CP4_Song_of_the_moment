<?php

namespace App\Domain\Model;

use App\Infrastructure\Persistence\Entity\Song;

interface DomainUserModelInterface
{
    public function getId(): ?int;
    public function getEmail(): ?string;
    public function getPassword(): string;

    public function setEmail(string $email): self;

    public function addFavorite(Song $favorites): self;
    public function removeFavorite(Song $favorites): self;
    public function isInFavorite(Song $song): bool;
}
