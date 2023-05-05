<?php

namespace App\Domain\Model;

interface DomainSongModelInterface
{
    public function getId(): ?int;

    public function getArtist(): ?string;

    public function getLinkYoutube(): ?string;

    public function getPhotoAlbum(): ?string;

    public function getIsApproved(): ?bool;
}
