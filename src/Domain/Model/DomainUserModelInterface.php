<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\Collection;

interface DomainUserModelInterface
{
    public function getId(): ?int;
    public function getUsername(): ?string;
    public function getPassword(): string;
    public function setUsername(string $username): self;
    public function addSongFavorite(DomainSongFavoriteModelInterface $songFavorite): self;
    public function removeSongFavorite(DomainSongFavoriteModelInterface $songFavorite): self;
    public function getSongFavorites(): Collection;
    public function isInFavorite(DomainSongModelInterface $song): bool;
}
