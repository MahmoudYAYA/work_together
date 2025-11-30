<?php

namespace App\Entity;

use App\Repository\BaieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BaieRepository::class)]
class Baie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column]
    private ?int $capaciteTotale = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCapaciteTotale(): ?int
    {
        return $this->capaciteTotale;
    }

    public function setCapaciteTotale(int $capaciteTotale): static
    {
        $this->capaciteTotale = $capaciteTotale;

        return $this;
    }
}
