<?php

namespace MyShop\DefaultBundle\Controller\API\REST;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Postman Chrome Extension
class ProductController extends Controller
{
    public function detailsAction($id)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id);

        $productArray = [
            'model' => $product->getModel(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'date' => $product->getDateCreatedAt()->format('d.m.Y')
        ];

        $response = new JsonResponse($productArray);
        return $response;
    }


    public function detailsXmlAction(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id);

        // плохой вариант
//        $res = '<product id='.$product->getId().'>';
//        $res .= '<model>' . $product->getModel() . '</model>';
//        $res .= '</product>';
        
        // нормальный вариант
        $xml = new \SimpleXMLElement("<product></product>");
        $xml->addAttribute("id", $product->getId());
        $xml->addChild("model", $product->getModel());
        $xml->addChild("price", $product->getPrice());
        $xml->addChild("description", $product->getDescription());
        $xml->addChild("date", $product->getDateCreatedAt()->format("d.m.Y"));

        $xmlStr = $xml->asXML();

        $response = new Response($xmlStr);
        return $response;
    }
}