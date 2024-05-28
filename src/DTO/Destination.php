<?php

declare(strict_types=1);

namespace App\DTO;

class Destination
{
    private ?int $id;

    private ?string $name;

    private ?string $description;

    private ?float $price;

    private ?int $duration;

    private ?string $image;

    public function __construct(?int $id, ?string $name, ?string $description, ?float $price, ?int $duration, ?string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->duration = $duration;
        $this->image = $image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
