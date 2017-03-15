<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

//    /**
//     * @Template()
//     * @Route("/product/details/{id}")
//     * @Entity("product", expr="repository.find(id)")
//    */
//    public function detailsAction()
//    {
//
//    }

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

    /**
     * @Template()
    */
    public function testfileAction(Request $request)
    {
        $dir = $this->get("kernel")->getRootDir() . "/../src/MyShop/DefaultBundle/DataFixtures/Files";
        $files = scandir($dir);

        $dirTo = $this->get("kernel")->getRootDir() . "/../web/photos/";

        $manager = $this->getDoctrine()->getManager();

        foreach ($files as $file)
        {
            if ($file !== "." && $file !== "..")
            {
                $fileFullPath =  $dir . "/" . $file;
                $fileFullPath = realpath($fileFullPath);

                copy($fileFullPath, $dirTo . $file);

                $product = new Product();
                $product->setModel(rand());
                $product->setPrice(rand());
                $product->setDescription(rand());
                $product->setIconFileName($file);

                $manager->persist($product);
                $manager->flush();
            }
        }


        die();


//        $files = $request->files; // $_FILES
//
//        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $myFile */
//        $myFile = $files->get("test_file");
//
//        $appDir = $this->get("kernel")->getRootDir() . "/../web/";
//        $myFile->move($appDir, $myFile->getClientOriginalName());

        return [];
    }
}

/***
/Users/igor/projects/school/myshop/src/MyShop/AdminBundle/Controller/ProductController.php:189:
object(Symfony\Component\HttpFoundation\FileBag)[14]
  protected 'parameters' =>
    array (size=1)
      'test_file' =>
        object(Symfony\Component\HttpFoundation\File\UploadedFile)[15]
          private 'test' => boolean false
          private 'originalName' => string 'Screen Shot 2017-03-15 at 19.10.01.png' (length=38)
          private 'mimeType' => string 'image/png' (length=9)
          private 'size' => int 48433
          private 'error' => int 0
          private 'pathName' (SplFileInfo) => string '/private/var/folders/9b/q3dspccj2zd15dpv8048_rg80000gn/T/php5GXGh0' (length=66)
          private 'fileName' (SplFileInfo) => string 'php5GXGh0' (length=9)













