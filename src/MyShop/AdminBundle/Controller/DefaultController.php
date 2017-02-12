<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template()
    */
    public function indexAction()
    {
        $this->forward("MyShopDefaultBundle:Default:index");

        return [];
    }
}
