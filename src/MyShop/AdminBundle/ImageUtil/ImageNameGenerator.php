<?php

namespace MyShop\AdminBundle\ImageUtil;


class ImageNameGenerator
{
    public function generateName()
    {
        return rand(100000000, 999999999);
    }
}