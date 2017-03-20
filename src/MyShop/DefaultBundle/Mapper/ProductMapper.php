<?php

namespace MyShop\DefaultBundle\Mapper;

use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class ProductMapper
{
    /**
     * @var ValidatorInterface
    */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return Product
     */
    public function arrayJsonRpcToProduct(array $data)
    {
        $product = new Product();

        $product->setModel($data["model");
        $product->setPrice($data["price");

        foreach ($data["images"] as $imageName)
        {
            $photo = new ProductPhoto();
            $photo->setFileName($imageName);
            $product->addPhoto($photo);
        }

        $this->validator->validate($product);

        return $product;
    }

    /**
     * @return Product
    */
    public function arrayCsvToProduct(array $data)
    {
        $product = new Product();

        $product->setModel($data[0);
        $product->setPrice($data[1);

        foreach ($data["images"] as $imageName)
        {
            $photo = new ProductPhoto();
            $photo->setFileName($imageName);
            $product->addPhoto($photo);
        }

        return $product;
    }
}