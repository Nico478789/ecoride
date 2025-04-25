<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departure_time = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arrival_time = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $number_of_seats = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 32)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

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

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departure_time;
    }

    public function setDepartureTime(\DateTimeInterface $departure_time): static
    {
        $this->departure_time = $departure_time;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrival_time;
    }

    public function setArrivalTime(\DateTimeInterface $arrival_time): static
    {
        $this->arrival_time = $arrival_time;

        return $this;
    }

    public function getNumberOfSeats(): ?int
    {
        return $this->number_of_seats;
    }

    public function setNumberOfSeats(int $number_of_seats): static
    {
        $this->number_of_seats = $number_of_seats;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }
}
