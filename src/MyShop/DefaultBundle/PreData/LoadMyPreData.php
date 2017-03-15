<?php

namespace MyShop\DefaultBundle\PreData;

use Doctrine\ORM\EntityManager;
use MyShop\AdminBundle\Entity\User;

class LoadMyPreData
{
    /**
     * @var EntityManager
    */
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function loadProducts()
    {
        // ...
    }

    public function loadUsers()
    {
        $u = new User();
        $u->setEmail("user@gmail.com");
        $u->setPassword("12312312312");
        $u->setUsername("admin");

        $this->manager->persist($u);
        $this->manager->flush();
    }
}