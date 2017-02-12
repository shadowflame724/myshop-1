<?php

namespace MyShop\AdminBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Eventviva\ImageResize;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use MyShop\DefaultBundle\Form\ProductPhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProductPhotoController extends Controller
{
    /**
     * @Template()
    */
    public function listAction($idProduct)
    {
        $product = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:Product")->find($idProduct);

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function addAction(Request $request, $idProduct)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository("MyShopDefaultBundle:Product")->find($idProduct);
        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }

        $photo = new ProductPhoto();
        $form = $this->createForm(ProductPhotoType::class, $photo);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $filesAr = $request->files->get("myshop_defaultbundle_productphoto");

            /** @var UploadedFile $photoFile */
            $photoFile = $filesAr["photoFile"];

            $checkImgService = $this->get("myshop_admin.check_img");
            try {
                $checkImgService->check($photoFile);
            } catch (\InvalidArgumentException $ex) {
                die("Image type error!");
            }

            $result = $this->get("myshop_admin.image_uploader")->uploadImage($photoFile, $idProduct);

            $photo->setSmallFileName($result->getSmallFileName());
            $photo->setFileName($result->getBigFileName());
            $photo->setProduct($product);

            $manager->persist($photo);
            $manager->flush();
        }

        return [
            "form" => $form->createView(),
            "product" => $product
        ];
    }
}