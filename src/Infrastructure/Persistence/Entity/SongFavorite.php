<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainSongFavoriteModelInterface;
use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Model\DomainUserModelInterface;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class SongFavorite implements DomainSongFavoriteModelInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "songFavorites")]
    #[ORM\JoinColumn(nullable: false)]
    private DomainUserModelInterface $user;

    #[ORM\ManyToOne(targetEntity: Song::class, inversedBy: "songFavorites")]
    #[ORM\JoinColumn(nullable: false)]
    private DomainSongModelInterface $song;

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

    public function getUser(): DomainUserModelInterface
    {
        return $this->user;
    }

    public function setUser(DomainUserModelInterface $user): void
    {
        $this->user = $user;
    }

    public function getSong(): DomainSongModelInterface
    {
        return $this->song;
    }

    public function setSong(DomainSongModelInterface $song): void
    {
        $this->song = $song;
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
