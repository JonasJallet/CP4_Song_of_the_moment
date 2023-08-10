<?php

namespace App\Domain\Model;

use DateTimeImmutable;

interface DomainSongFavoriteModelInterface
{
    public function getId(): int;
    public function setId(int $id): void;
    public function getUser(): DomainUserModelInterface;
    public function setUser(DomainUserModelInterface $user): void;
    public function getSong(): DomainSongModelInterface;
    public function setSong(DomainSongModelInterface $song);
    public function getCreatedAt(): ?DateTimeImmutable;
    public function setCreatedAt(?DateTimeImmutable $createdAt): void;
}
