<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use JsonSerializable;

#[ORM\Entity(repositoryClass: TourRepository::class)]
#[Broadcast]
class Tour implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaHora = null;

    #[ORM\OneToMany(mappedBy: 'tour', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\OneToOne(mappedBy: 'tour', cascade: ['persist', 'remove'])]
    private ?Informe $informe = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ruta $ruta = null;

    #[ORM\Column]
    private ?bool $disponible = false;
    //si pongo el atributo $disponible a true, el tour estará disponible por defecto?

    public function __construct()
    {
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaHora(): ?\DateTimeInterface
    {
        return $this->fechaHora;
    }

    // public function setFechaHora(\DateTimeInterface $fechaHora): static
    // {
    //     $this->fechaHora = $fechaHora;

    //     return $this;
    // }

    public function setFechaHora(\DateTime $fechaHora): static
    {
        $hora = (int) $fechaHora->format('H');
        $minutos = (int) $fechaHora->format('i');

        if ($minutos >= 30) {
            $hora++;
        }

        $fechaHora->setTime($hora, 0);

        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * @return Collection<int, reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(reserva $reserva): static
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas->add($reserva);
            $reserva->setTour($this);
        }

        return $this;
    }

    public function removeReserva(reserva $reserva): static
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getTour() === $this) {
                $reserva->setTour(null);
            }
        }

        return $this;
    }

    public function getInforme(): ?Informe
    {
        return $this->informe;
    }

    public function setInforme(Informe $informe): static
    {
        // set the owning side of the relation if necessary
        if ($informe->getTour() !== $this) {
            $informe->setTour($this);
        }

        $this->informe = $informe;

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

    public function getRuta(): ?Ruta
    {
        return $this->ruta;
    }

    public function setRuta(?Ruta $ruta): static
    {
        $this->ruta = $ruta;

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getRuta()->getNombre().'-'.$this->getFechaHora()->format('Y-m-d H:i:s');
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'fechaHora' => $this->getFechaHora(),
            'ruta' => $this->getRuta(),
            'usuario' => $this->getUsuario(),
            'disponible' => $this->isDisponible(),
        ];
    }
}
