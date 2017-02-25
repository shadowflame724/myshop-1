<?php

namespace MyShop\DefaultBundle\Controller\API\JsonRPC;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JsonRpcController extends Controller
{
    public function indexAction(Request $request)
    {
        $requestJson = $request->getContent();
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
        return [
            'id' => $product->getId(),
            'model' => $product->getModel(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'date' => $product->getDateCreatedAt()->format('d.m.Y')
        ];
    }
}