<?php

namespace MyShop\DefaultBundle\Controller\API\JsonRPC;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRpcController extends Controller
{
    public function indexAction(Request $request)
    {
        $requestJson = $request->getContent();
//
//        $data = json_decode($requestJson, true);
//
//        if ($data["method"] == "show_product")
//        {
//            $idProduct = $data["params"]["idProduct"];
//
//            $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($idProduct);
//
//            $responseAr = [
//                "id" => $product->getId(),
//                "model" => $product->getModel(),
//                "price" => $product->getPrice()
//            ];
//
//            $fullResponse = [
//                "jsonrpc" => "2.0",
//                "result" => $responseAr,
//                "id" => $data["id"]
//            ];
//
////            $responseJson = json_encode($fullResponse);
////            $response = new Response($responseJson);
////            return $response;
//
//            return new JsonResponse($fullResponse);
//
//        }
//
//        die();




        $requestAr = @json_decode($requestJson, true);
        if ($requestAr === null) {
            return new JsonResponse([
                'jsonrpc' => '2.0',
                'error' => [
                    'code' => -32700,
                    'message' => 'Wrong json format'
                ]
            ]);
        }

        if (isset($requestAr['method'])) {
            $method = $requestAr['method'];
            $responseParamsAr = $this->$method($requestAr['params']);
            $responseAr = [
                'jsonrpc' => '2.0',
                'result' => $responseParamsAr,
                'id' => $requestAr['id']
            ];
            return new JsonResponse($responseAr);
        } else {
            if (isset($requestAr[0]['method'])) {
                $result = [];
                foreach ($requestAr as $reqAr) {
                    $method = $reqAr['method'];
                    $responseParamsAr = $this->$method($reqAr['params']);
                    $responseAr = [
                        'jsonrpc' => '2.0',
                        'result' => $responseParamsAr,
                        'id' => $reqAr['id']
                    ];
                    $result[] = $responseAr;
                }
                return new JsonResponse($result);
            }
        }
    }

    public function categoryDetails($params)
    {
        $id = $params['categoryId'];
        $category = $this->getDoctrine()->getRepository('MyShopDefaultBundle:Category')->find($id);
        return [
            'name' => $category->getName()
        ];
    }

    public function productDetails($params)
    {
        $productId = $params['productId'];
        $product = $this->getDoctrine()->getRepository('MyShopDefaultBundle:Product')->find($productId);

//        $json = json_encode($product);

//        $json = $this->get("serializer")->serialize($product, "json");
//        echo $json;
//        die();

//        return $json;

        return [
            'id' => $product->getId(),
            'model' => $product->getModel(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'date' => $product->getDateCreatedAt()->format('d.m.Y')
        ];
    }
}