<?php
namespace App\Controller;

use App\Service\BillService;
use App\Service\BillProductService;
use App\Service\ProductService;
use App\Dto\BillProductsDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class BillController extends AbstractController
{

    private $billService;
    private $billProductService;
    private $em;

    public function __construct(
        BillService $billService,
        BillProductService $billProductService,
        EntityManagerInterface $em)
    {
        $this->billService = $billService;
        $this->billProductService = $billProductService;
        $this->em = $em;
    }

    /**
     * @Route("api/bill/create/", name="api_bill_create", methods={"POST"})
     */
    public function createBill(Request $request)
    {
        $response = new JsonResponse();

        $bill_json = $request->getContent();
        $bill_array = json_decode($bill_json);

        $bill_product_DTO = $this->billService->create($bill_array);

        if($bill_product_DTO == false)
        {
            $response->setData([
                'success' => false,
                'message' => 'Bill not created',
            ]);

            return $response;
        }

        $products_id_array = $bill_product_DTO->getProductIdArray();
        $bill = $bill_product_DTO->getBill();
        $bill_array = $bill->__toArray();

        foreach($products_id_array as $k => $v)
        {
            $bill_product_OBJ = $this->billProductService->getBillProductById($v);
            $product_dto = $this->billService->billProductOBJToProductDTO($bill_product_OBJ);
            $productDTO_array = $product_dto->__toArray();
            $bill_array['products'][] = $productDTO_array;
        }

        $response->setData($bill_array);
        return $response;
    }

    /**
     * @Route("api/bill/list/", name="api_bill_list", methods={"GET"})
     */
    public function list()
    {
        $response = new JsonResponse();

        $bill_array = $this->billService->list();

        $response->setData($bill_array);
        return $response;
    }

    /**
     * @Route("api/bill/show/{id}", name="api_bill_by_id", methods={"GET"})
     */
    public function getBillbyId($id)
    {
        $response = new JsonResponse();

        $bill = $this->billService->getBillbyId($id);

        if($bill == null)
        {
            $response->setData([
                'success' => false,
                'message' => 'Bill not found',
            ]);

            return $response;
        }

        $bill_product_obj_array = $this->billService->getAllBillProductsByBillId($id);

        $bill_array = $bill->__toArray();

        foreach($bill_product_obj_array as $k => $bill_product_obj)
        {
            $product_dto = $this->billService->billProductOBJToProductDTO($bill_product_obj);
            $productDTO_array = $product_dto->__toArray();
            $bill_array['products'][] = $productDTO_array;
        }

        $response->setData($bill_array);
        return $response;
    }

}