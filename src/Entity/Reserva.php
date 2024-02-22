<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
#[Broadcast]
class Reserva implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nEntradas = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tour $tour = null;

    #[ORM\OneToOne(mappedBy: 'reserva', cascade: ['persist', 'remove'])]
    private ?Valoracion $valoracion = null;

    #[ORM\OneToMany(mappedBy: 'reserva', targetEntity: Usuario::class)]
    private Collection $usuarios;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(nullable: true)]
    private ?int $nAsistentes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaReserva = null;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNEntradas(): ?int
    {
        return $this->nEntradas;
    }

    public function setNEntradas(int $nEntradas): static
    {
        $this->nEntradas = $nEntradas;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): static
    {
        $this->tour = $tour;

        return $this;
    }

    public function getValoracion(): ?Valoracion
    {
        return $this->valoracion;
    }

    public function setValoracion(Valoracion $valoracion): static
    {
        // set the owning side of the relation if necessary
        if ($valoracion->getReserva() !== $this) {
            $valoracion->setReserva($this);
        }

        $this->valoracion = $valoracion;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Usuario $usuario): static
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios->add($usuario);
            $usuario->addReserva($this);
        }

        return $this;
    }

    public function removeUsuario(Usuario $usuario): static
    {
        if ($this->usuarios->removeElement($usuario)) {
            // set the owning side to null (unless already changed)
            if ($usuario->getReservas() === $this) {
                $usuario->addReserva(new Reserva());
            }
        }

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getNAsistentes(): ?int
    {
        return $this->nAsistentes;
    }

    public function setNAsistentes(?int $nAsistentes): static
    {
        $this->nAsistentes = $nAsistentes;

        return $this;
    }

    public function getFechaReserva(): ?\DateTimeInterface
    {
        return $this->fechaReserva;
    }

    public function setFechaReserva(\DateTimeInterface $fechaReserva): static
    {
        $this->fechaReserva = $fechaReserva;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'nEntradas' => $this->getNEntradas(),
            'tour' => $this->getTour(),
            'valoracion' => $this->getValoracion(),
            'usuarios' => $this->getUsuarios(),
            'usuario' => $this->getUsuario(),
            'nAsistentes' => $this->getNAsistentes(),
            'fechaReserva' => $this->getFechaReserva(),
        ];
    }

}
