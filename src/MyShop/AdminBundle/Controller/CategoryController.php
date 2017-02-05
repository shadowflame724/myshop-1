<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Category;
use MyShop\DefaultBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Template()
    */
    public function listAction()
    {
        $categoryList = $this->getDoctrine()
            ->getManager()
            ->createQuery("select hello from MyShopDefaultBundle:Category hello where hello.parentCategory is null")
            ->getResult();

        return ["categoryList" => $categoryList];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request, $idParent = null)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();

            if ($idParent !== null)
            {
                $parentCat = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:Category")->find($idParent);
                $category->setParentCategory($parentCat);
            }

            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("my_shop_admin.category_list");
        }

        return [
            "form" => $form->createView(),
            "idParent" => $idParent
        ];
    }

    /**
     * @Template()
     */
    public function editAction()
    {
        return [];
    }

    public function deleteAction()
    {
        return [];
    }
}
