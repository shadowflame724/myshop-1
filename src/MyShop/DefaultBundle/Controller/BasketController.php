<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Customer;
use MyShop\DefaultBundle\Entity\CustomerOrder;
use MyShop\DefaultBundle\Entity\OrderProduct;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\CustomerOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BasketController extends Controller
{
    /**
     * @Template()
    */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:CustomerOrder')->getOrCreateOrder($customer);

        return ['order' => $order];
    }

    /**
     * @Template()
    */
    public function confirmAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:CustomerOrder')->getOrCreateOrder($customer);

        $form = $this->createForm(CustomerOrderType::class, $order);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $order->setStatus(CustomerOrder::STATUS_DONE);
            $manager->persist($order);
            $manager->flush();

            return $this->redirectToRoute("myshop.main_page");
        }

        return [
            'form' => $form->createView(),
            'order' => $order
        ];
    }

    public function addProductToBasketAction($idProduct)
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:CustomerOrder')->getOrCreateOrder($customer);

        $dql = 'select p from MyShopDefaultBundle:OrderProduct p where p.order = :orderCustomer and p.idProduct = :idProduct';

        /** @var OrderProduct $productOrder */
        $productOrder = $manager->createQuery($dql)->setParameters([
            'idProduct' => $idProduct,
            'orderCustomer' => $order
        ])->getOneOrNullResult();

        if ($productOrder !== null)
        {
            $count = $productOrder->getCount();
            $productOrder->setCount($count + 1);

            $manager->persist($productOrder);
            $manager->flush();
            return $this->redirectToRoute("myshop.main_page");
        }
        else {
            $productShop = $manager->getRepository("MyShopDefaultBundle:Product")->find($idProduct);

            $productOrder = new OrderProduct();
            $productOrder->setCount(1);
            $productOrder->setModel($productShop->getModel());
            $productOrder->setPrice($productShop->getPrice());
            $productOrder->setIdProduct($productShop->getId());
            $productOrder->setOrder($order);

            $manager->persist($productOrder);
            $manager->flush();
            return $this->redirectToRoute("myshop.main_page");
        }
    }
}
