<?php

namespace MyShop\AdminBundle\ImageUtil;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CheckImg extends BaseCheck
{
    /*
    array (size=2)
      0 =>
        array (size=2)
          0 => string 'jpg' (length=3)
          1 => string 'image/jpg' (length=9)
      1 =>
        array (size=2)
          0 => string 'gif' (length=3)
          1 => string 'image/gif' (length=9)
     * */

    public function check(UploadedFile $photoFile)
    {
        $this->checkMimeType($photoFile);

        $fileExt = $photoFile->getClientOriginalExtension();
        $checkTrue = false;
        foreach ($this->supportImageTypeList as $imgType) {
            if ($fileExt == $imgType[0]) {
                $checkTrue = true;
            }
        }

        if ($checkTrue == false) {
            throw new \InvalidArgumentException("Extension is blocked!");
        }

        return true;
    }
}