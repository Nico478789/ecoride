<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $whereFrom = null;

    #[ORM\Column(length: 32)]
    private ?string $whereTo = null;

    /**
     * @var Collection<int, user>
     */
    #[ORM\ManyToMany(targetEntity: user::class, inversedBy: 'rides')]
    private Collection $passenger;

    public function __construct()
    {
        $this->passenger = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWhereFrom(): ?string
    {
        return $this->whereFrom;
    }

    public function setWhereFrom(string $whereFrom): static
    {
        $this->whereFrom = $whereFrom;

        return $this;
    }

    public function getWhereTo(): ?string
    {
        return $this->whereTo;
    }

    public function setWhereTo(string $whereTo): static
    {
        $this->whereTo = $whereTo;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getPassenger(): Collection
    {
        return $this->passenger;
    }

    public function addPassenger(user $passenger): static
    {
        if (!$this->passenger->contains($passenger)) {
            $this->passenger->add($passenger);
        }

        return $this;
    }

    public function removePassenger(user $passenger): static
    {
        $this->passenger->removeElement($passenger);

        return $this;
    }
}
