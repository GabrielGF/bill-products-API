<?php

namespace App\Entity;

use App\Repository\BillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BillRepository::class)
 */
class Bill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $items_quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity=BillProduct::class, mappedBy="bill")
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getItemsQuantity(): ?int
    {
        return $this->items_quantity;
    }

    public function setItemsQuantity(int $items_quantity): self
    {
        $this->items_quantity = $items_quantity;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
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
            $billProduct->setBill($this);
        }

        return $this;
    }

    public function removeBillProduct(BillProduct $billProduct): self
    {
        if ($this->bill_product->removeElement($billProduct)) {
            // set the owning side to null (unless already changed)
            if ($billProduct->getBill() === $this) {
                $billProduct->setBill(null);
            }
        }

        return $this;
    }

    public function __toArray()
    {
        $bill = [];
        $bill['id'] = $this->id;
        $bill['date'] = $this->date;
        $bill['items_quantity'] = $this->items_quantity;
        $bill['total'] = $this->total;
        //$bill['products'] = $this->bill_product;

        return $bill;
    }
}
