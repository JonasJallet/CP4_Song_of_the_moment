<?php

namespace App\Domain\Model;

interface DomainUserModelInterface
{
    public function getId(): ?int;
    public function getUsername(): ?string;
    public function getPassword(): string;
    public function setUsername(string $username): self;
    public function addFavorite(DomainSongModelInterface $favorites): self;
    public function removeFavorite(DomainSongModelInterface $favorites): self;
    public function isInFavorite(DomainSongModelInterface $song): bool;
}
