<?php

namespace App\Domain\Model;

use DateTimeImmutable;

interface DomainSongPlaylistModelInterface
{
    public function getId(): int;
    public function getSong(): DomainSongModelInterface;
    public function setSong(DomainSongModelInterface $song): void;
    public function getPlaylist(): DomainPlaylistModelInterface;
    public function setPlaylist(DomainPlaylistModelInterface $playlist): void;
    public function getCreatedAt(): ?DateTimeImmutable;
    public function setCreatedAt(?DateTimeImmutable $createdAt): void;
}
