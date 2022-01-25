<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["product:list", "product:show"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["product:show"])]
    private $brand;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["product:list", "product:show"])]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["product:show"])]
    private $OS;

    #[ORM\Column(type: 'integer')]
    #[Groups(["product:show"])]
    private $storage;

    #[ORM\Column(type: 'integer')]
    #[Groups(["product:show"])]
    private $RAM;

    #[ORM\Column(type: 'float')]
    #[Groups(["product:show"])]
    private $screenSize;

    #[ORM\Column(type: 'integer')]
    #[Groups(["product:show"])]
    private $weight;

    #[ORM\Column(type: 'float')]
    #[Groups(["product:show"])]
    private $depth;

    #[ORM\Column(type: 'float')]
    #[Groups(["product:show"])]
    private $width;

    #[ORM\Column(type: 'float')]
    #[Groups(["product:show"])]
    private $height;

    #[ORM\Column(type: 'integer')]
    #[Groups(["product:show"])]
    private $battery;

    #[ORM\Column(type: 'string', length: 10)]
    #[Groups(["product:show"])]
    private $connectivity;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["product:show"])]
    private $microSD;

    #[ORM\Column(type: 'string', length: 10)]
    #[Groups(["product:show"])]
    private $color;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["product:show"])]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["product:show"])]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOS(): ?string
    {
        return $this->OS;
    }

    public function setOS(string $OS): self
    {
        $this->OS = $OS;

        return $this;
    }

    public function getStorage(): ?int
    {
        return $this->storage;
    }

    public function setStorage(int $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getRAM(): ?int
    {
        return $this->RAM;
    }

    public function setRAM(int $RAM): self
    {
        $this->RAM = $RAM;

        return $this;
    }

    public function getScreenSize(): ?float
    {
        return $this->screenSize;
    }

    public function setScreenSize(float $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getDepth(): ?float
    {
        return $this->depth;
    }

    public function setDepth(float $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getBattery(): ?int
    {
        return $this->battery;
    }

    public function setBattery(int $battery): self
    {
        $this->battery = $battery;

        return $this;
    }

    public function getConnectivity(): ?string
    {
        return $this->connectivity;
    }

    public function setConnectivity(string $connectivity): self
    {
        $this->connectivity = $connectivity;

        return $this;
    }

    public function getMicroSD(): ?bool
    {
        return $this->microSD;
    }

    public function setMicroSD(bool $microSD): self
    {
        $this->microSD = $microSD;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
