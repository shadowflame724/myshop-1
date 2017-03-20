<?php

namespace MyShop\DefaultBundle\Convertor;

use MyShop\DefaultBundle\Entity\Page;
use MyShop\DefaultBundle\Entity\Product;

class ProductConvertor
{
    /**
     * @return Product
    */
    public function convertPageToProduct(Page $page, $price)
    {
        $product = new Product();
        $product->setModel($page->getTitle());
        $product->setPrice($price);
        $product->setDescription($page->getContent());



        return $product;
    }
}