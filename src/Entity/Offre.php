<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $nombreUnites = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $prixMensuel = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $prixAnnuelle = null;

    #[ORM\Column(nullable: true)]
    private ?int $redctionAnnuelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNombreUnites(): ?int
    {
        return $this->nombreUnites;
    }

    public function setNombreUnites(int $nombreUnites): static
    {
        $this->nombreUnites = $nombreUnites;

        return $this;
    }

    public function getPrixMensuel(): ?string
    {
        return $this->prixMensuel;
    }

    public function setPrixMensuel(string $prixMensuel): static
    {
        $this->prixMensuel = $prixMensuel;

        return $this;
    }

    public function getPrixAnnuelle(): ?string
    {
        return $this->prixAnnuelle;
    }

    public function setPrixAnnuelle(string $prixAnnuelle): static
    {
        $this->prixAnnuelle = $prixAnnuelle;

        return $this;
    }

    public function getRedctionAnnuelle(): ?int
    {
        return $this->redctionAnnuelle;
    }

    public function setRedctionAnnuelle(?int $redctionAnnuelle): static
    {
        $this->redctionAnnuelle = $redctionAnnuelle;

        return $this;
    }
}
