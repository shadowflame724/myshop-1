<?php

namespace MyShop\AdminBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
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
            $mimeType = $photoFile->getClientMimeType();
            if ($mimeType !== "image/jpeg" and $mimeType !== "image/jpg" and $mimeType !== "image/gif" and $mimeType !== "image/png") {
                throw new InvalidArgumentException("MimeType is blocked!");
            }

            $fileExt = $photoFile->getClientOriginalExtension();
            if ($fileExt !== "jpg" and $fileExt !== "png" and $fileExt !== "gif") {
                throw new InvalidArgumentException("Extension is blocked!");
            }

            $photoFileName = $product->getId() . rand(1000000, 9999999) . "." . $photoFile->getClientOriginalExtension();
            $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/";

            $photoFile->move($photoDirPath, $photoFileName);

            $photo->setFileName($photoFileName);
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