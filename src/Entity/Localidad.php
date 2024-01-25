<?php

namespace App\Entity;

use App\Repository\LocalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: LocalidadRepository::class)]
#[Broadcast]
class Localidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    // #[ORM\OneToMany(mappedBy: 'localidad', targetEntity: Ruta::class)]
    // private Collection $rutas;

    #[ORM\ManyToOne(inversedBy: 'localidades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?provincia $provincia = null;

    #[ORM\OneToMany(mappedBy: 'localidad', targetEntity: Item::class)]
    private Collection $items;

    public function __construct()
    {
        // $this->rutas = new ArrayCollection();
        $this->items = new ArrayCollection();
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

    /**
     * @return Collection<int, Ruta>
     */
    // public function getRutas(): Collection
    // {
    //     return $this->rutas;
    // }

    // public function addRuta(Ruta $ruta): static
    // {
    //     if (!$this->rutas->contains($ruta)) {
    //         $this->rutas->add($ruta);
    //         $ruta->setLocalidad($this);
    //     }

    //     return $this;
    // }

    // public function removeRuta(Ruta $ruta): static
    // {
    //     if ($this->rutas->removeElement($ruta)) {
    //         // set the owning side to null (unless already changed)
    //         if ($ruta->getLocalidad() === $this) {
    //             $ruta->setLocalidad(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getProvincia(): ?provincia
    {
        return $this->provincia;
    }

    public function setProvincia(?provincia $provincia): static
    {
        $this->provincia = $provincia;

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
            $item->setLocalidad($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getLocalidad() === $this) {
                $item->setLocalidad(null);
            }
        }

        return $this;
    }
}
