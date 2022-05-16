<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $mgcId;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $vendor;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $model;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $VendorCode;

    #[ORM\Column(type: 'integer')]
    private $DealerId;

    #[ORM\Column(type: 'integer')]
    private $inStock;

    #[ORM\Column(type: 'boolean')]
    private $Available;

    #[ORM\Column(type: 'boolean')]
    private $Downloadable;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Foto;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Picture;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'text', nullable: true)]
    private $annotation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $category;

    #[ORM\Column(type: 'integer')]
    private $parentCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMgcId(): ?int
    {
        return $this->mgcId;
    }

    public function setMgcId(int $mgcId): self
    {
        $this->mgcId = $mgcId;

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

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(?string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getModel(): ?int
    {
        return $this->model;
    }

    public function setModel(?int $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getVendorCode(): ?string
    {
        return $this->VendorCode;
    }

    public function setVendorCode(?string $VendorCode): self
    {
        $this->VendorCode = $VendorCode;

        return $this;
    }

    public function getDealerId(): ?int
    {
        return $this->DealerId;
    }

    public function setDealerId(int $DealerId): self
    {
        $this->DealerId = $DealerId;

        return $this;
    }

    public function getInStock(): ?int
    {
        return $this->inStock;
    }

    public function setInStock(int $inStock): self
    {
        $this->inStock = $inStock;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->Available;
    }

    public function setAvailable(bool $Available): self
    {
        $this->Available = $Available;

        return $this;
    }

    public function isDownloadable(): ?bool
    {
        return $this->Downloadable;
    }

    public function setDownloadable(bool $Downloadable): self
    {
        $this->Downloadable = $Downloadable;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->Foto;
    }

    public function setFoto(?string $Foto): self
    {
        $this->Foto = $Foto;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->Picture;
    }

    public function setPicture(?string $Picture): self
    {
        $this->Picture = $Picture;

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

    #[ORM\PrePersist]
    public function setTimestamp(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }

    public function getAnnotation(): ?string
    {
        return $this->annotation;
    }

    public function setAnnotation(?string $annotation): self
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getParentCategory(): ?int
    {
        return $this->parentCategory;
    }

    public function setParentCategory(int $parentCategory): self
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }
}
