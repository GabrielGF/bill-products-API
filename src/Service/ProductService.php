<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{

    private $productRepository;
    private $em;

    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $em)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    public function list()
    {
        $products = $this->productRepository->findAll();

        $products_array = [];
        foreach ($products as $product) 
        {
            $product_array = [
                'id' => $product->getId(),
                'description' => $product->getDescription(),
                'quantity' => $product->getQuantity(),
                'unit_price' => $product->getUnitPrice(),
                'date' => $product->getDate(),
            ];
            array_push($products_array, $product_array);
        }

        return $products_array;
    }

    public function getProductById($id)
    {
        $product = $this->productRepository->find($id);

        return $product;
    }

    public function createProduct($data)
    {
        $product_array = json_decode($data);

        if($product_array->quantity <= 0 || $product_array->unit_price <= 0)
        {
            return null;
        }

        $product_obj = new Product();
        $product_obj->setDescription($product_array->description);
        $product_obj->setQuantity($product_array->quantity);
        $product_obj->setUnitPrice($product_array->unit_price);
        $product_obj->setDate(new \DateTime());

        $this->em->persist($product_obj);
        $this->em->flush();

        return $product_obj;
    }

    public function deleteProduct($id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) 
        {
            return null;
        }

        $this->em->remove($product);
        $this->em->flush();

        return true;
    }

    public function updateProduct($id, $data)
    {
        $product = $this->productRepository->find($id);

        if (!$product) 
        {
            return null;
        }

        $product_array = json_decode($data);

        if($product_array->quantity <= 0 || $product_array->unit_price <= 0)
        {
            return false;
        }

        $product->setDescription($product_array->description);
        $product->setQuantity($product_array->quantity);
        $product->setUnitPrice($product_array->unit_price);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }
}
