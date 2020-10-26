<?php

namespace App\Service;

use App\Entity\Bill;
use App\Entity\Product;
use App\Entity\ProductBill;
use App\Repository\BillProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class BillProductService
{

    private $em;
    private $billProductRepository;

    public function __construct(
        EntityManagerInterface $em,
        BillProductRepository $billProductRepository
        )
    {
        $this->em = $em;
        $this->billProductRepository = $billProductRepository;
    }

    public function getBillProductById($id)
    {
        $bill_product_obj = $this->billProductRepository->find($id);

        if (!$bill_product_obj) 
        {
            return null;
        }

        return $bill_product_obj;
    }

    public function deleteBillProduct($id)
    {
        $bill_product_obj = $this->em->getRepository(BillProduct::class)->find($id);

        if (!$bill_product_obj) 
        {
            return null;
        }

        $this->em->remove($bill_product_obj);
        $this->em->flush();

        return true;
    }
}