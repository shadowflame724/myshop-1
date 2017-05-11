<?php

namespace MyShop\DefaultBundle\Controller;

use GuzzleHttp\Client;
use MyShop\DefaultBundle\Entity\Page;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\PageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\User;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $products = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->findAll();
        return $this->render("@MyShopDefault/Default/index.html.twig", [
            'productList' => $products
        ]);
    }

    public function createSomeProductAction()
    {
        $product = new Product();
        $product->setModel("J5");
        $product->setPrice(200);
        $product->setDescription("Great mobile phone");

        $doctine = $this->getDoctrine();
        $manager = $doctine->getManager();

        $manager->persist($product);
        $manager->flush();

        $response = new Response();
        $response->setContent($product->getId());
        return $response;
    }

    /**
     * @Template()
    */
    public function showProductAction(Request $request, $id)
    {

        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");
        $product = $repository->find($id);

        if ($product == null)
            throw new NotFoundHttpException();

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function showProductListAction($sortPrice = 1)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

//        $dql = 'select p from MyShopDefaultBundle:Product p order by p.price asc';
//        $productList = $this->getDoctrine()->getManager()->createQuery($dql)->getResult();

        if ($sortPrice == 1)
            $productList = $repository->findBy([], ['price' => 'desc'], 20);
        else
            $productList = $repository->findBy([], ['price' => 'asc']);



        return [
            "productList" => $productList
        ];
    }

    public function clientCurlAction($idProduct)
    {
        // GUZZLE CLIENT
//        $client = new Client();
//        $response = $client->request("POST", "http://127.0.0.1:8000/api/jsonrpc");
//
//        var_dump($response);


        // CURL CLIENT
//        $client = $this->get("curl_client");
//
//        $request = [
//            'jsonrpc' => '2.0',
//            'method' => 'productDetails',
//            'params' => ['productId' => $idProduct],
//            'id' => rand()
//        ];
//
//        $jsonRequest = json_encode($request);
//
//        $response = $client->send($jsonRequest);
//
//        return new Response($response);
    }
}