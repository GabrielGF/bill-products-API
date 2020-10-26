<?php

namespace App\Entity;

use App\Repository\BillProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BillProductRepository::class)
 */
class BillProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Bill::class, inversedBy="bill_product")
     * @ORM\JoinColumn(name="bill_id", referencedColumnName="id", nullable=false)
     */
    private $bill;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="bill_product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toArray()
    {
        $bill_product = [];
        $bill_product['id'] = $this->id;
        $bill_product['quantity'] = $this->quantity;
        $bill_product['subtotal'] = $this->subtotal;
        $bill_product['date'] = $this->date;

        return $bill_product;
    }
}
