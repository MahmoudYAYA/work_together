<?php

namespace App\Entity;

use App\Repository\UniteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $numero = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'libre';

    #[ORM\Column(length: 20)]
    private ?string $etat = 'OK';

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $nomPersonnalise = null;

    #[ORM\ManyToOne(inversedBy: 'unites')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Baie $baie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getNomPersonnalise(): ?string
    {
        return $this->nomPersonnalise;
    }

    public function setNomPersonnalise(?string $nomPersonnalise): static
    {
        $this->nomPersonnalise = $nomPersonnalise;
        return $this;
    }

    public function getBaie(): ?Baie
    {
        return $this->baie;
    }

    public function setBaie(?Baie $baie): static
    {
        $this->baie = $baie;
        return $this;
    }

    /**
     * Retourne l'emplacement complet (ex: B001-U05)
     */
    public function getEmplacement(): string
    {
        return $this->baie?->getNumero() . '-' . $this->numero;
    }
}