<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Bill;
use App\Entity\BillProduct;
use App\Service\ProductService;
use App\Service\BillProductService;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\BillProductsDTO;
use App\Dto\ProductDTO;
use App\Repository\BillRepository;

class BillService
{

    private $productService;
    private $em;
    private $billProductService;
    private $billRepository;

    public function __construct(
        ProductService $productService,
        BillProductService $billProductService,
        EntityManagerInterface $em,
        BillRepository $billRepository)
    {
        $this->productService = $productService;
        $this->em = $em;
        $this->billProductService = $billProductService;
    }

    public function create($data)
    {
        //creo la factura
        $bill = new Bill();
        $bill->setDate(new \DateTime());
        $bill->setItemsQuantity(0);
        $bill->setTotal(0);
        $this->em->persist($bill);
        $this->em->flush();

        $i = 0;
        $bill_products_DTO = new BillProductsDTO();
        $item_quantity = 0;
        $total_bill = 0;
        $bill_product_id = [];
        $product_array = [];

        foreach($data as $bill_array)
        {
            $product_id = $bill_array->product_id;
            $product = $this->productService->getProductById($product_id);

            $product_quatity = $bill_array->quantity;

            if($product)
            {
                $result = $this->quantityAvailable($product, $product_quatity);

                if($result == true)
                {
                    //Creo detalle de la factura
                    $bill_product = new BillProduct();
                    $bill_product->setProduct($product);
                    $bill_product->setBill($bill);
                    $bill_product->setQuantity($product_quatity);
                    $bill_product->setDescription($product->getDescription());
                    $bill_product->setSubtotal($product_quatity * $product->getUnitPrice());
                    $bill_product->setDate(new \DateTime());
                    $this->em->persist($bill_product);
                    $this->em->flush();

                    $item_quantity = $item_quantity + $product_quatity;
                    $total_bill = $total_bill + ($product->getUnitPrice() * $product_quatity) ;
                    $bill_product_id[$i] = $bill_product->getId();
                    //producto y cantidad que se setea al billproduct
                    //lo debo devolver cuando falla la creación
                    $product_array[$i]['id'] = $product->getId();
                    $product_array[$i]['quantity'] = $product_quatity;
                    $bill_products_DTO->setProductIdArray('bill_product_id_' . $i, $bill_product_id);
                    $i++;
                }
                else
                {
                    $i = 0;
                    //quitar los BillProduct en el persist de doctrine
                    $this->em->clear();
                    //elimino las entities guardadas generadas antes del error
                    foreach($bill_product_id as $k => $v)
                    {
                        $this->billProductService->deleteBillProduct($v);
                    }
                    $this->deleteBill($bill);

                    //devuelvo a los products los que les habia restado
                    //cuando se creo el billproduct
                    foreach($product_array as $product)
                    {
                        foreach($product as $k1)
                        {
                            $productID = $k1['id'];
                            $productOBJ = $this->productService->getProductById($productID);
                            $productOBJ->setQuantity($productOBJ->getQuantity() + $k1['quantity']);

                            $this->em->persist($productOBJ);
                            $this->em->flush();
                        }
                    }

                    return false;
                }
            }
        }

        $bill->setTotal($total_bill);
        $bill->setItemsQuantity($item_quantity);
        $this->em->persist($bill);
        $this->em->flush();

        $bill_products_DTO->setBill($bill);
        return $bill_products_DTO;
    }

    private function quantityAvailable(Product $product, $quantity)
    {
        $product_quantity = $product->getQuantity();

        if($quantity > 0 && $product_quantity >= $quantity)
        {
            $product->setQuantity($product_quantity - $quantity);
            $this->em->persist($product);
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function deleteBill(Bill $bill)
    {
        $id = $bill->getId();
        $bill_obj = $this->em->getRepository(Bill::class)->find($id);

        if (!$bill_obj) 
        {
            return null;
        }

        $this->em->remove($bill_obj);
        $this->em->flush();
        //falta eliminar todos losBillProductsrelacionados

        return true;
    }

    public function billProductOBJToProductDTO($bill_product)
    {
        $productDTO = new ProductDTO();
        $productDTO->setId($bill_product->getProduct()->getId());
        $productDTO->setDescription($bill_product->getProduct()->getDescription());
        $productDTO->setQuantity($bill_product->getQuantity());
        $productDTO->setUnitPrice($bill_product->getProduct()->getUnitPrice());
        $productDTO->setSubtotal($bill_product->getQuantity() * $bill_product->getProduct()->getUnitPrice());

        return $productDTO;
    }

    public function list()
    {
        $bills= $this->em->getRepository(Bill::class)->findAll();

        $bill_array = [];
        foreach ($bills as $bill) 
        {
            $bill_array['bills'][] = $bill->__toArray();
        }

        return $bill_array;
    }

    public function getBillById($id)
    {
        $bill= $this->em->getRepository(Bill::class)->find($id);

        if(!$bill)
        {
            return null;
        }

        return $bill;
    }

    public function getAllBillProductsByBillId($id)
    {
        $bill_product_OBJ_array = $this->em->getRepository(BillProduct::class)
                                    ->findBy(['bill'=> $id]);  
        
        return $bill_product_OBJ_array;
    }
}