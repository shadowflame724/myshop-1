<?php

namespace MyShop\AdminBundle\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductStorage
{
    /**
     * @var EntityManagerInterface
    */
    private $manager;

    /**
     * @var PaginatorInterface
    */
    private $pag;


    public function __construct(EntityManagerInterface $manager, PaginatorInterface $pag)
    {
        $this->manager = $manager;
        $this->pag = $pag;
    }

    public function getProductListPagination($page = 1, $countPerPage = 5)
    {
        $dql = "select p, c from MyShopDefaultBundle:Product p join p.category c";
        $query = $this->manager->createQuery($dql);

        $result = $this->pag->paginate($query, $page, $countPerPage);

        return $result;
    }
}