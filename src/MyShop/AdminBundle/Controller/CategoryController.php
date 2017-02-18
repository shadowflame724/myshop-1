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
        $categoryList = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:Category")->findAll();

        $res = [];

        /** @var Category $cat */
        foreach ($categoryList as $cat)
        {
            $parentId = '#';
            if ($cat->getParentCategory() !== null) {
                $parentId = $cat->getParentCategory()->getId();
            }

            $res[] = [
                'id' => $cat->getId(),
                'parent' => $parentId,
                'text' => $cat->getName(),
                'state' => ['opened' => true]
            ];
        }

        return [
            "catDataJson" => json_encode($res)
        ];
    }

    /**
     * @Template()
    */
    public function listAction($idParentCategory = null)
    {
        $manager = $categoryList = $this->getDoctrine()->getManager();
        $result = [];
        $viewData = [];

        if ($idParentCategory !== null) {
            $dql = "select hello from MyShopDefaultBundle:Category hello where hello.parentCategory = :idParent";
            $result = $manager->createQuery($dql)
                ->setParameter("idParent", $idParentCategory)
                ->getResult();

            $viewData["categoryCurrent"] = $manager->getRepository("MyShopDefaultBundle:Category")->find($idParentCategory);
        } else {
            $dql = "select hello from MyShopDefaultBundle:Category hello where hello.parentCategory is null";
            $result = $manager->createQuery($dql)->getResult();
        }

        $viewData["categoryList"] = $result;

        return $viewData;
    }

    /**
     * @Template()
     */
    public function addAction(Request $request, $idParent = null)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $this->getParameter("my_test_param");

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
