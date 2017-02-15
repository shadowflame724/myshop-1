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
    public function treeAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->findAll();

        $results = [];
        /** @var Category $cat */
        foreach ($categoryList as $cat)
        {
            if ($cat->getParentCategory() !== null) {
                $idParent = $cat->getParentCategory()->getId();
            } else {
                $idParent = "#";
            }

            $results[] = [
                "id" => $cat->getId(),
                "parent" => $idParent,
                "text" => $cat->getName() . " <a href='#'>[X]</a><a href='#'>[E]</a>"
            ];
        }

        $dataJson = json_encode($results);

        return [
            "categoryListJson" => $dataJson
        ];
    }

    /**
     * @Template()
    */
    public function listAction($idParentCategory = null)
    {
        $manager = $categoryList = $this->getDoctrine()->getManager();
        $viewData = [];

        if (is_null($idParentCategory))
        {
            $viewData["categoryList"] = $manager->createQuery("select cat from MyShopDefaultBundle:Category cat where cat.parentCategory is null")->getResult();
        }
        else {
            $parentCategory = $manager->getRepository("MyShopDefaultBundle:Category")->find($idParentCategory);
            $viewData["categoryList"] = $parentCategory->getChildrenCategories();
            $viewData["categoryParent"] = $parentCategory;
        }

        return $viewData;
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

            return $this->redirectToRoute("my_shop_admin.category_list", ['idParentCategory' => $idParent]);
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
