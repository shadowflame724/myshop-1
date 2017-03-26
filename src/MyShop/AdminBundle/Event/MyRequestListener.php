<?php

namespace MyShop\AdminBundle\Event;

use MyShop\AdminBundle\Controller\DefaultController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class MyRequestListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        //var_dump($controller);

        if ($controller[0] instanceof DefaultController) {

        }

    }
}