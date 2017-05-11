<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Customer;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    /**
     * @Template()
    */
    public function indexAction(Request $request)
    {
        $searchKey = $request->get("searchKey", "");

        $dql = 'select p from MyShopDefaultBundle:Product p where 
              p.model like :search_key or
              p.description like :search_key';

        $productList = $this->getDoctrine()
            ->getManager()
            ->createQuery($dql)
            ->setParameter('search_key', "%" . $searchKey . "%")
            ->getResult();

        return [
            'productList' => $productList
        ];
    }
}
