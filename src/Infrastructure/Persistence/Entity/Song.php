<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainSongModelInterface;
use App\Domain\Model\DomainSongPlaylistModelInterface;
use App\Infrastructure\Persistence\Repository\SongRepository;
use App\Infrastructure\Service\CustomUuidGenerator;
use App\Infrastructure\Validator\Constraint\SongConstraint;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SongRepository::class)]
#[ORM\UniqueConstraint(name: "unique_song_title_artist", columns: ["title", "artist"])]
#[ORM\HasLifecycleCallbacks]
#[SongConstraint]
class Song implements DomainSongModelInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: CustomUuidGenerator::class)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le titre saisie {{ titre }} est trop long, il ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $title = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 150,
        maxMessage: 'L\' artiste saisie {{ artiste }} est trop long, il ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $artist = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'L\' album saisie {{ album }} est trop long, il ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $album = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le lien saisie {{ photo_album }} est trop long, il ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $linkYoutube = null;

    #[ORM\Column]
    private bool $linkYoutubeValid = true;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le lien saisie {{ link_youtube }} est trop long, il ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $photoAlbum = null;

    #[ORM\Column]
    private ?bool $isApproved = false;

    #[MaxDepth(2)]
    #[ORM\OneToMany(
        mappedBy: "playlist",
        targetEntity: SongPlaylist::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $songPlaylists;
    #[ORM\OneToMany(
        mappedBy: 'song',
        targetEntity: SongFavorite::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $songFavorites;

    #[ORM\Column(length: 255, unique: true)]
    #[Slug(fields: ['artist', 'title'])]
    private ?string $slug = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function __construct()
    {
        $this->songPlaylists = new ArrayCollection();
        $this->songFavorites = new ArrayCollection();
    }

    public function getId(): ?string
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(string $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getLinkYoutube(): ?string
    {
        return $this->linkYoutube;
    }

    public function setLinkYoutube(string $linkYoutube): self
    {
        $this->linkYoutube = $linkYoutube;

        return $this;
    }

    public function getPhotoAlbum(): ?string
    {
        return $this->photoAlbum;
    }

    public function setPhotoAlbum(string $photoAlbum): self
    {
        $this->photoAlbum = $photoAlbum;

        return $this;
    }

    public function isIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    /**
     * @return bool|null
     */
    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * @return Collection<int, DomainSongPlaylistModelInterface>
     */
    public function getPlaylists(): Collection
    {
        return $this->songPlaylists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->songPlaylists->contains($playlist)) {
            $this->songPlaylists->add($playlist);
            $playlist->addSong($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->songPlaylists->removeElement($playlist)) {
            $playlist->removeSong($this);
        }

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLinkYoutubeValid(): bool
    {
        return $this->linkYoutubeValid;
    }

    /**
     * @param bool $linkYoutubeValid
     */
    public function setLinkYoutubeValid(bool $linkYoutubeValid): void
    {
        $this->linkYoutubeValid = $linkYoutubeValid;
    }

    /**
     * @return Collection<int, SongFavorite>
     */
    public function getSongFavorites(): Collection
    {
        return $this->songFavorites;
    }

    public function addSongFavorite(SongFavorite $songFavorite): self
    {
        if (!$this->songFavorites->contains($songFavorite)) {
            $this->songFavorites->add($songFavorite);
            $songFavorite->setSong($this);
        }

        return $this;
    }

    public function removeSongFavorite(SongFavorite $songFavorite): self
    {
        $this->songFavorites->removeElement($songFavorite);

        return $this;
    }
}
