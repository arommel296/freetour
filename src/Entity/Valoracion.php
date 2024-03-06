<?php

namespace App\Entity;

use App\Repository\ValoracionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ValoracionRepository::class)]
#[Broadcast]
class Valoracion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'La valoración del guía debe estar entre {{ min }} estrellas y {{ max }} estrellas',
    )]
    private ?int $notaGuia = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'La valoración de la ruta debe estar entre {{ min }} estrellas y {{ max }} estrellas',
    )]
    private ?int $notaRuta = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comentario = null;

    #[ORM\OneToOne(inversedBy: 'valoracion', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?reserva $reserva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotaGuia(): ?int
    {
        return $this->notaGuia;
    }

    public function setNotaGuia(int $notaGuia): static
    {
        $this->notaGuia = $notaGuia;

        return $this;
    }

    public function getNotaRuta(): ?int
    {
        return $this->notaRuta;
    }

    public function setNotaRuta(int $notaRuta): static
    {
        $this->notaRuta = $notaRuta;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getReserva(): ?reserva
    {
        return $this->reserva;
    }

    public function setReserva(reserva $reserva): static
    {
        $this->reserva = $reserva;

        return $this;
    }
}
