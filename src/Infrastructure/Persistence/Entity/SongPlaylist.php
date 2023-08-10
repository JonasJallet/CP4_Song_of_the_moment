<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Model\DomainSongPlaylistModelInterface;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class SongPlaylist implements DomainSongPlaylistModelInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Song::class, fetch: "EAGER", inversedBy: "songPlaylists")]
    private DomainSongModelInterface $song;

    #[ORM\ManyToOne(targetEntity: Playlist::class, fetch: "EAGER", inversedBy: "songPlaylists")]
    private DomainPlaylistModelInterface $playlist;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSong(): DomainSongModelInterface
    {
        return $this->song;
    }

    public function setSong(DomainSongModelInterface $song): void
    {
        $this->song = $song;
    }

    public function getPlaylist(): DomainPlaylistModelInterface
    {
        return $this->playlist;
    }

    public function setPlaylist(DomainPlaylistModelInterface $playlist): void
    {
        $this->playlist = $playlist;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
