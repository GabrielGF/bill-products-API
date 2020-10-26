<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $unit_price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=BillProduct::class, mappedBy="product")
     */
    private $bill_product;

    public function __construct()
    {
        $this->bill_product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setUnitPrice(float $unit_price): self
    {
        $this->unit_price = $unit_price;

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

    public function __toArray()
    {
        $product = array();
        $product['id'] = $this->id;
        $product['description'] = $this->description;
        $product['quantity'] = $this->quantity;
        $product['unit_price'] = $this->unit_price;
        $product['date'] = $this->date;

        return $product;
    }

    /**
     * @return Collection|BillProduct[]
     */
    public function getBillProduct(): Collection
    {
        return $this->bill_product;
    }

    public function addBillProduct(BillProduct $billProduct): self
    {
        if (!$this->bill_product->contains($billProduct)) {
            $this->bill_product[] = $billProduct;
            $billProduct->setProduct($this);
        }

        return $this;
    }

    public function removeBillProduct(BillProduct $billProduct): self
    {
        if ($this->bill_product->removeElement($billProduct)) {
            // set the owning side to null (unless already changed)
            if ($billProduct->getProduct() === $this) {
                $billProduct->setProduct(null);
            }
        }

        return $this;
    }
}
