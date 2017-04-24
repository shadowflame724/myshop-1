<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\CustomerOrder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersController extends Controller
{
    /**
     * @Template()
    */
    public function listAction($page = 1)
    {
//        $orders = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:CustomerOrder")
//            ->findBy([], ["dateCreatedAt" => "desc"]);

        $query = $this->getDoctrine()
            ->getManager()
            ->createQuery("select o from MyShopDefaultBundle:CustomerOrder o");

        $orders = $this->get("knp_paginator")->paginate($query, $page, 5);

        return ["orders" => $orders];
    }

//      ПЛОХО !!!
//    public function changeStatusAction(CustomerOrder $order, $status)
//    {
//        if ($status == CustomerOrder::STATUS_RESOLVE)
//        {
//            // ....
//        }
//
//        $manager = $this->getDoctrine()->getManager();
//        $order->setStatus($status);
//        $manager->persist($order);
//        $manager->flush($order);
//
//        $this->addFlash("success", "Заказ сохранен!");
//
//        return $this->redirectToRoute("my_shop_admin.orders_list");
//    }

    /**
     * @Template()
    */
    public function productsAction(CustomerOrder $order)
    {
        return [
            'order' => $order
        ];
    }

    public function productRemoveAction(CustomerOrder $order, $idProduct)
    {
        $manager = $this->getDoctrine()->getManager();

        $orderProduct = $manager->getRepository("MyShopDefaultBundle:OrderProduct")->findOneBy([
            'id' => $idProduct,
            'order' => $order
        ]);
        $manager->remove($orderProduct);
        $manager->flush();
        return $this->redirectToRoute("my_shop_admin.order_products", ['id' => $order->getId()]);

// ==========================================================

//        $dql = 'delete from MyShopDefaultBundle:OrderProduct p where p.id = :idProduct and p.order = :customerOrder ';
//        $manager->createQuery($dql)->setParameters([
//            'customerOrder' => $order,
//            'idProduct' => $idProduct
//        ])->execute();
//        return $this->redirectToRoute("my_shop_admin.order_products", ['id' => $order->getId()]);
//
// ==========================================================

//        $products = $order->getProducts();
//        foreach ($products as $prod)
//        {
//            if ($prod->getId() == $idProduct)
//            {
//                $manager->remove($prod);
//                $manager->flush();
//
//                return $this->redirectToRoute("my_shop_admin.order_products", ['id' => $order->getId()]);
//            }
//        }
    }

    public function resolveAction(CustomerOrder $order)
    {
        $manager = $this->getDoctrine()->getManager();
        $order->setStatus(CustomerOrder::STATUS_RESOLVE);
        $manager->persist($order);
        $manager->flush();

        $this->addFlash("success", "Заказ обработан!");

        return $this->redirectToRoute("my_shop_admin.orders_list");
    }

    public function rejectAction(CustomerOrder $order)
    {
        $manager = $this->getDoctrine()->getManager();
        $order->setStatus(CustomerOrder::STATUS_REJECT);
        $manager->persist($order);
        $manager->flush();

        $this->addFlash("success", "Заказ отклонен!");

        return $this->redirectToRoute("my_shop_admin.orders_list");
    }
}