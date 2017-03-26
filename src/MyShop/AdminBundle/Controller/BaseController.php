<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseController extends Controller
{
    protected function getManager()
    {
        $manager = $this->getDoctrine()->getManager();
    }
}