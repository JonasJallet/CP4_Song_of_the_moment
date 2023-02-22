<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le titre saisie {{ titre }} est trop longue, il ne doit pas dépasser {{ limit }} caractères',
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

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le lien saisie {{ link_youtube }} est trop long, il ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $photoAlbum = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorites')]
    #[ORM\OrderBy(["id" => "DESC"])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }
}
