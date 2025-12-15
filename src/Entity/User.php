<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $dateCreation;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $dateModification;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $actif = true;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
        $this->dateModification = new \DateTimeImmutable();
        $this->actif = true;
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        $this->dateModification = new \DateTimeImmutable();
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        $this->dateModification = new \DateTimeImmutable();
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        $this->dateModification = new \DateTimeImmutable();
        return $this;
    }

    /**
     * Sérialise l'objet pour le stockage en session
     * Remplace le mot de passe par un hash CRC32C pour la sécurité
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password ?? '');
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // Deprecated depuis Symfony 7.3
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        $this->dateModification = new \DateTimeImmutable();
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        $this->dateModification = new \DateTimeImmutable();
        return $this;
    }

    public function getDateCreation(): \DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function getDateModification(): \DateTimeImmutable
    {
        return $this->dateModification;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;
        $this->dateModification = new \DateTimeImmutable();
        return $this;
    }

    /**
     * Retourne le nom complet de l'utilisateur
     */
    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Vérifie si l'utilisateur est Admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN');
    }

    /**
     * Vérifie si l'utilisateur est Client
     */
    public function isClient(): bool
    {
        return $this->hasRole('ROLE_CLIENT');
    }

    /**
     * Vérifie si l'utilisateur est Comptable
     */
    public function isComptable(): bool
    {
        return $this->hasRole('ROLE_COMPTABLE');
    }

    /**
     * Change le mot de passe avec hashage sécurisé
     */
    public function changerMotDePasse(string $nouveauPassword): void
    {
        $this->password = password_hash($nouveauPassword, PASSWORD_BCRYPT);
        $this->dateModification = new \DateTimeImmutable();
    }

    /**
     * Désactive le compte utilisateur
     */
    public function desactiver(): void
    {
        $this->actif = false;
        $this->dateModification = new \DateTimeImmutable();
    }

    /**
     * Réactive le compte utilisateur
     */
    public function activer(): void
    {
        $this->actif = true;
        $this->dateModification = new \DateTimeImmutable();
    }
}
