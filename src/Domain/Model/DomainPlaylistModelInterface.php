<?php

namespace App\Domain\Model;

use Doctrine\Common\Collections\Collection;

interface DomainPlaylistModelInterface
{
    public function getId(): string;
    public function getUser(): ?DomainUserModelInterface;
    public function setUser(?DomainUserModelInterface $user): self;
    public function getSongPlaylists(): Collection;
    public function addSong(DomainSongPlaylistModelInterface $songPlaylist): self;
    public function removeSong(DomainSongPlaylistModelInterface $songPlaylist): self;
    public function getName(): ?string;
    public function setName(string $name): self;
}
