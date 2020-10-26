<?php
namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{

    /*No es necesario inyectar dependecia en el constructor de la clase
    Puede pasarse como parametro del método
    config/service.yml -> autowire: true */
    private $productService;
    private $em;

    public function __construct(
        ProductService $productService,
        EntityManagerInterface $em)
    {
        $this->productService = $productService;
        $this->em = $em;
    }
    
    /**
     * @Route("api/product/list/", name="api_product_list", methods={"GET"})
     */
    public function productList()
    {
        $products = $this->productService->list();
        $response = new JsonResponse();

        if(!empty($products))
        {
            $response->setData($products);
            return $response;
        }

        $response->setData([
            'success' => false,
            'message' => 'Product list not found',
        ]);

       return $response;
    }

    /**
     * @Route("api/product/create/", name="api_product_create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $response = new JsonResponse();

        $product_json = $request->getContent();
        $product = $this->productService->createProduct($product_json);

        if($product == null)
        {
            $product_array = json_decode($product_json);

            $response->setData([
                'success' => false,
                'message' => 'Quantity and Unit Price properties must be greater than or equal to zero',
                'quantity' => $product_array->quantity,
                'unit_price' => $product_array->unit_price
            ]);

            return $response;
        }

        $response->setData($product->__toArray());

        return $response;
    }

    /**
     * @Route("api/product/delete/{id}", name="api_product_delete", methods={"DELETE"})
     */
    public function delete($id)
    {
        $response = new JsonResponse();
        $product = $this->productService->deleteProduct($id);

        if ($product == true) {
            
            $response->setData([
                'success' => true,
                'message' => 'Product deleted succesfully',
                'id' => $id
            ]);

            return $response;
        }

        $response->setData([
            'success' => false,
            'message' => 'Product not found',
            'id' => $id
        ]);

        return $response;
    }

    /**
     * @Route("api/product/show/{id}", name="api_product_by_id", methods={"GET"})
     */
    public function productById($id)
    {
        $response = new JsonResponse();

        $product = $this->productService->getProductById($id);

        if (!$product) {
            $response->setData([
                'success' => false,
                'message' => 'Product not found',
                'id' => $id
            ]);

            return $response;
        }

        $response->setData($product->__toArray());

        return $response;
    }

    /**
     * @Route("api/product/update/{id}", name="api_product_update", methods={"PUT"})
     */
    public function updateProduct(Request $request, $id)
    {
        $response = new JsonResponse();

        $product_json = $request->getContent();
        $product = $this->productService->updateProduct($id, $product_json);

        if ($product === null) {
            $response->setData([
                'success' => false,
                'message' => 'Product not found',
                'id' => $id
            ]);

            return $response;
        }

        if($product === false)
        {
            $product_array = json_decode($product_json);

            $response->setData([
                'success' => false,
                'message' => 'Quantity and Unit Price properties must be greater than or equal to zero',
                'quantity' => $product_array->quantity,
                'unit_price' => $product_array->unit_price
            ]);

            return $response;
        }

        $product = $product->__toArray();
        $response->setData($product);

        return $response;
    }
}