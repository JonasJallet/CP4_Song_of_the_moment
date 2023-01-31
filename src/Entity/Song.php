<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 150)]
    private ?string $artist = null;

    #[ORM\Column(length: 255)]
    private ?string $album = null;

    #[ORM\Column(length: 255)]
    private ?string $link_youtube = null;

    #[ORM\Column(length: 255)]
    private ?string $photo_album = null;

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->link_youtube;
    }

    public function setLinkYoutube(string $link_youtube): self
    {
        $this->link_youtube = $link_youtube;

        return $this;
    }

    public function getPhotoAlbum(): ?string
    {
        return $this->photo_album;
    }

    public function setPhotoAlbum(string $photo_album): self
    {
        $this->photo_album = $photo_album;

        return $this;
    }
}
