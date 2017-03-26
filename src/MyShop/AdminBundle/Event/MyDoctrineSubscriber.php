<?php

namespace MyShop\AdminBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class MyDoctrineSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'postPersist'
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (method_exists($entity, "getTitle"))
        {
            file_put_contents("simple_map.html", "<b>" . $entity->getTitle() . "</b>");
        }
    }
}