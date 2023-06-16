<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\Collection;

interface DomainPlaylistModelInterface
{
    public function getId(): string;
    public function getUser(): ?DomainUserModelInterface;
    public function setUser(?DomainUserModelInterface $user): self;
    public function getSongs(): Collection;
    public function addSong(DomainSongModelInterface $song): self;
    public function removeSong(DomainSongModelInterface $song): self;
    public function getName(): ?string;
    public function setName(string $name): self;
}
