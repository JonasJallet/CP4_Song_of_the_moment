<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Model\DomainUserModelInterface;
use App\Infrastructure\Persistence\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this email')]
class User implements DomainUserModelInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(
        message: 'l\'email {{ value }} n\'est pas valide.',
    )]
    #[Assert\NotBlank(message: 'L\'email ne peut pas être vide.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'L\'email saisi est trop long,
        il ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    #[Assert\NotNull(message: 'Le mot de passe est obligatoire')]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'users')]
    private Collection $favorites;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        $this->validatePassword($context);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Song $favorites): self
    {
        if (!$this->favorites->contains($favorites)) {
            $this->favorites->add($favorites);
        }

        return $this;
    }

    public function removeFavorite(Song $favorites): self
    {
        $this->favorites->removeElement($favorites);

        return $this;
    }

    public function isInFavorite(Song $song): bool
    {
        return $this->favorites->contains($song);
    }

    public function validatePassword(ExecutionContextInterface $context): void
    {
        $password = $this->getPassword();
        $errors = [];

        if (isset($this->password)) {
            if (strlen($password) < 12) {
                $errors[] = 'Au moins 12 caractères';
            }

            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Au moins une majuscule';
            }

            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = 'Au moins une minuscule';
            }

            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Au moins un chiffre';
            }
        }

        foreach ($errors as $error) {
            $context->buildViolation($error)
                ->atPath('password')
                ->addViolation();
        }
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
