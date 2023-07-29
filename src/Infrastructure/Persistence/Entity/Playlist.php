<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainPlaylistModelInterface;
use App\Domain\Model\DomainSongModelInterface;
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

    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'playlists')]
    private Collection $songs;

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
        $this->songs = new ArrayCollection();
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
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(DomainSongModelInterface $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
        }

        return $this;
    }

    public function removeSong(DomainSongModelInterface $song): self
    {
        $this->songs->removeElement($song);

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
