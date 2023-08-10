<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Model\DomainSongPlaylistModelInterface;
use App\Domain\Model\DomainUserModelInterface;
use App\Infrastructure\Persistence\Repository\PlaylistRepository;
use App\Infrastructure\Service\CustomUuidGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist implements DomainPlaylistModelInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: CustomUuidGenerator::class)]
    private ?string $id = null;

    #[MaxDepth(2)]
    #[ORM\ManyToOne(inversedBy: 'playlists')]
    private ?User $user = null;

    #[ORM\OneToMany(
        mappedBy: "playlist",
        targetEntity: SongPlaylist::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[ORM\OrderBy(["createdAt" => "DESC"])]
    private Collection $songPlaylists;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
    #[Assert\Length(
        max: 45,
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Slug(fields: ['id', 'name'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->songPlaylists = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?DomainUserModelInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, SongPlaylist>
     */
    public function getSongPlaylists(): Collection
    {
        return $this->songPlaylists;
    }

    public function addSong(DomainSongPlaylistModelInterface $songPlaylist): self
    {
        if (!$this->songPlaylists->contains($songPlaylist)) {
            $this->songPlaylists->add($songPlaylist);
        }

        return $this;
    }

    public function removeSong(DomainSongPlaylistModelInterface $songPlaylist): self
    {
        $this->songPlaylists->removeElement($songPlaylist);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
