<?php

namespace App\Dto;

class ProductDTO
{
    private $id;
    private $description;
    private $unitPrice;
    private $quantity;
    private $subtotal;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unit_price;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function __toArray()
    {
        $productDTO_array = [];
        $productDTO_array['id'] = $this->id;
        $productDTO_array['description'] = $this->description;
        $productDTO_array['unit_price'] = $this->unitPrice;
        $productDTO_array['quantity'] = $this->quantity;
        $productDTO_array['subtotal'] = $this->subtotal;

        return $productDTO_array;
    }
}