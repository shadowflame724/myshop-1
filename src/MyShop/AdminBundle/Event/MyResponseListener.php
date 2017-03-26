<?php

namespace MyShop\AdminBundle\Event;

use MyShop\AdminBundle\Controller\BaseApiController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class MyResponseListener
{
    private $controller;

    public function onKernelController(FilterControllerEvent $event)
    {
        $this->controller = $event->getController();
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        //$response->setContent($response->getContent() . "<br /><div style='border: 1px solid red; width: 400px; height: 200px'>ADS</div>");

        if ($this->controller[0] instanceof BaseApiController)
        {
            $responseData = $response->getContent();
            if (is_array($responseData))
            {
                $response->setContent(json_encode($responseData));
            }
        }
    }
}