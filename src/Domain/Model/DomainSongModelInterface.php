<?php

namespace App\Domain\Model;

interface DomainSongModelInterface
{
    public function getId(): ?string;
    public function getArtist(): ?string;
    public function getAlbum(): ?string;
    public function getPhotoAlbum(): ?string;
    public function getLinkYoutube(): ?string;
    public function getIsApproved(): ?bool;

    public function setArtist(string $artist): self;
    public function setAlbum(string $album): self;
    public function setPhotoAlbum(string $photoAlbum): self;
    public function setLinkYoutube(string $linkYoutube): self;
    public function setIsApproved(bool $isApproved): self;

    public function isIsApproved(): ?bool;
}
