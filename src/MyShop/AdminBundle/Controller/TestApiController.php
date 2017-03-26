<?php

namespace MyShop\AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

class TestApiController extends BaseApiController
{
    public function indexAction()
    {
        $data = [
            "var1" => "value1"
        ];

        $response = new Response($data);

        return $response;
    }
}