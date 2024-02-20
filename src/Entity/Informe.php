<?php

namespace App\Entity;

use App\Repository\InformeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: InformeRepository::class)]
#[Broadcast]
class Informe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $foto = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observaciones = null;

    #[ORM\Column]
    private ?float $dinero = null;

    #[ORM\OneToOne(inversedBy: 'informe', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tour $tour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): static
    {
        $this->foto = $foto;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): static
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getDinero(): ?float
    {
        return $this->dinero;
    }

    public function setDinero(float $dinero): static
    {
        $this->dinero = $dinero;

        return $this;
    }

    public function getTour(): ?tour
    {
        return $this->tour;
    }

    public function setTour(tour $tour): static
    {
        $this->tour = $tour;

        return $this;
    }
}
