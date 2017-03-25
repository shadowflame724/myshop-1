<?php

namespace MyShop\AdminBundle\ImageUtil;

use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class BaseCheck
{
    protected $supportImageTypeList;

    public function __construct($sp)
    {
        $this->supportImageTypeList = $sp;
    }

    public function checkMimeType(UploadedFile $photoFile)
    {
        $checkTrue = false;
        $mimeType = $photoFile->getClientMimeType();
        foreach ($this->supportImageTypeList as $imgType) {
            if ($mimeType == $imgType[1]) {
                $checkTrue = true;
            }
        }
        if ($checkTrue !== true) {
            throw new \InvalidArgumentException("Mime type is blocked!");
        }
    }
}