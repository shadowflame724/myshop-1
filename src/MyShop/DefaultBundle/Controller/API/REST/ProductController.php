<?php

namespace MyShop\DefaultBundle\Controller\API\REST;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function detailsXmlAction(Request $request, Product $product)
    {
        $xml = new \SimpleXMLElement("<product></product>");
        $xml->addAttribute("id", $product->getId());
        $xml->addChild('model', $product->getModel());
        $xml->addChild('price', $product->getPrice());
        $xml->addChild('description', $product->getDescription());
        $xml->addChild('date', $product->getDateCreatedAt()->format('d.m.Y'));

        $baseUrl = $request->getScheme() . '://' . $request->getHttpHost() . '/photos/';

        if ($product->getIconFileName() !== "") {
            $xml->addChild('icon', $baseUrl . $product->getIconFileName());
        }

        return new Response($xml->asXML());
    }

    public function detailsJsonAction(Request $request, Product $product)
    {
        return new JsonResponse([
            'id' => $product->getId(),
            'model' => $product->getModel(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'date' => $product->getDateCreatedAt()->format('d.m.Y')
        ]);
    }
}