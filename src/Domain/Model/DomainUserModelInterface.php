<?php

namespace App\Domain\Model;

use App\Infrastructure\Persistence\Entity\SongFavorite;
use Doctrine\Common\Collections\Collection;

interface DomainUserModelInterface
{
    public function getId(): ?int;
    public function getUsername(): ?string;
    public function getPassword(): string;
    public function setUsername(string $username): self;
    public function addSongFavorite(SongFavorite $songFavorite): self;
    public function removeSongFavorite(SongFavorite $songFavorite): self;
    public function getSongFavorites(): Collection;
    public function isInFavorite(DomainSongModelInterface $song): bool;
}
