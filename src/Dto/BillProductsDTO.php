<?php

namespace App\Dto;

use App\Entity\Bill;

class BillProductsDTO
{
    private $bill;
    private $product_id_array;

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function __construct() 
    {
        $this->product_id_array = [];
    }

    public function setProductIdArray($prop, $value) 
    {
        $this->product_id_array[$prop] = $value;
    }

    public function getProductIdArray() 
    {
        return $this->product_id_array;
    }

}