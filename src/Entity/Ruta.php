<?php

namespace App\Entity;

use App\Repository\RutaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RutaRepository::class)]
#[Broadcast]
class Ruta implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $nombre = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $coordInicio = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $descripcion = null;

    #[ORM\ManyToMany(targetEntity: Item::class)]
    #[Assert\NotBlank]
    private Collection $items;

    #[ORM\OneToMany(mappedBy: 'ruta', targetEntity: Tour::class)]
    private Collection $tours;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank]
    private ?string $foto = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $inicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $fin = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    private ?int $aforo = null;

    #[ORM\Column(nullable: true)]
    private ?array $programacion = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->tours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCoordInicio(): ?string
    {
        return $this->coordInicio;
    }

    public function setCoordInicio(string $coordInicio): static
    {
        $this->coordInicio = $coordInicio;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }


    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return Collection<int, Tour>
     */
    public function getTours(): Collection
    {
        return $this->tours;
    }

    public function addTour(Tour $tour): static
    {
        if (!$this->tours->contains($tour)) {
            $this->tours->add($tour);
            $tour->setRuta($this);
        }

        return $this;
    }

    public function removeTour(Tour $tour): static
    {
        if ($this->tours->removeElement($tour)) {
            // set the owning side to null (unless already changed)
            if ($tour->getRuta() === $this) {
                $tour->setRuta(null);
            }
        }

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): static
    {
        $this->foto = $foto;

        return $this;
    }

    public function getInicio(): ?\DateTimeInterface
    {
        return $this->inicio;
    }

    public function setInicio(?\DateTimeInterface $inicio): static
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): static
    {
        $this->fin = $fin;

        return $this;
    }

    public function getAforo(): ?int
    {
        return $this->aforo;
    }

    public function setAforo(?int $aforo): static
    {
        $this->aforo = $aforo;

        return $this;
    }

    public function getProgramacion(): ?array
    {
        return $this->programacion;
    }

    public function setProgramacion(?array $programacion): static
    {
        $this->programacion = $programacion;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNombre();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'coordInicio' => $this->getCoordInicio(),
            'descripcion' => $this->getDescripcion(),
            'items' => $this->getItems(),
            'tours' => $this->getTours(),
            'foto' => $this->getFoto(),
            'inicio' => $this->getInicio(),
            'fin' => $this->getFin(),
            'aforo' => $this->getAforo(),
            'programacion' => $this->getProgramacion()
        ];
    }

}
