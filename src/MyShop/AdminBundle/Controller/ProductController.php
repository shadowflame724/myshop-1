<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ProductController extends Controller
{
//    private $manager;
//
//    public function __construct()
//    {
//        $this->manager = $this->getDoctrine()->getManager();
//    }

    public function deleteAction($id)
    {
        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute("my_shop_admin.product_list");
    }

    public function listByCategoryAction($id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $productList = $category->getProductList();

        return $this->render("@MyShopAdmin/Product/list.html.twig", [
            "productList" => $productList
        ]);
    }

    /**
     * @Template()
    */
    public function listAction()
    {
        $productList = $this->getDoctrine()
            ->getManager()
            ->createQuery("select p, c from MyShopDefaultBundle:Product p join p.category c")
            ->getResult();

        //$this->get("session")->set("history", $this->get("session")->get("history") . "<br />product list");

        return ["productList" => $productList];
    }

    /**
     * @Template()
    */
    public function editAction(Request $request, Product $product)
    {
        //$product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id);

        $form = $this->createForm(ProductType::class, $product);

        /******************************************/
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $filesAr = $request->files->get("myshop_defaultbundle_product");

                if (isset($filesAr["iconFile"])) {
                    /** @var UploadedFile $photoFile */
                    $photoFile = $filesAr["iconFile"];

                    $checkImgService = $this->get("myshop_admin.check_img");
                    try {
                        $checkImgService->check($photoFile);
                    } catch (\InvalidArgumentException $ex) {
                        $this->addFlash("error", "Не верный тип картинки");
                        return $this->redirectToRoute('my_shop_admin.product_add');
                    }

                    $photoFileName = rand(1000000, 9999999) . "." . $photoFile->getClientOriginalExtension();
                    $photoFile->move($this->get("kernel")->getRootDir() . "/../web/photos/", $photoFileName);
                    $product->setIconFileName($photoFileName);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();

                //$this->get("session")->set("history", $this->get("session")->get("history") . "<br />product edit");

                return $this->redirectToRoute("my_shop_admin.product_list");
            }
        }
        /******************************************/

        return [
            "form" => $form->createView(),
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        /******************************************/
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                /** @var ConstraintViolationList $errorList */
                $errorList = $this->get('validator')->validate($product);
                if ($errorList->count() > 0) {
                    /** @var ConstraintViolation $error */
                    foreach ($errorList as $error) {
                        $this->addFlash("error", ucfirst($error->getPropertyPath()) . ': ' . $error->getMessage());
                    }
                    return $this->redirectToRoute("my_shop_admin.product_add");
                }

                $filesAr = $request->files->get("myshop_defaultbundle_product");

                if (isset($filesAr["iconFile"])) {
                    /** @var UploadedFile $photoFile */
                    $photoFile = $filesAr["iconFile"];

                    $checkImgService = $this->get("myshop_admin.check_img");
                    try {
                        $checkImgService->check($photoFile);
                    } catch (\InvalidArgumentException $ex) {
                        $this->addFlash("error", "Не верный тип картинки");
                        return $this->redirectToRoute('my_shop_admin.product_add');
                    }

                    $photoFileName = rand(1000000, 9999999) . "." . $photoFile->getClientOriginalExtension();
                    $photoFile->move($this->get("kernel")->getRootDir() . "/../web/photos/", $photoFileName);
                    $product->setIconFileName($photoFileName);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();

                return $this->redirectToRoute("my_shop_admin.product_list");
            }
        }
        /******************************************/

        return [
            "form" => $form->createView()
        ];
    }
}
