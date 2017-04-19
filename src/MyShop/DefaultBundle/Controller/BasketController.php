<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Customer;
use MyShop\DefaultBundle\Entity\CustomerOrder;
use MyShop\DefaultBundle\Entity\OrderProduct;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\CustomerOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

    public function recalculationCurrentOrderAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $this->getUser();
        $order = $manager->getRepository('MyShopDefaultBundle:CustomerOrder')->getOrCreateOrder($customer);

        $products = $order->getProducts();
        /** @var OrderProduct $product */
        foreach ($products as $product)
        {
            $key = "prod_" . $product->getId();
            $productCount = $request->get($key);
            $productCount = intval($productCount);

            if ($productCount < 0) {
                $product->setCount(1);
            }
            elseif ($productCount == 0) {
                $this->orderRemoveProductAction($product);
            }
            else {
                $product->setCount($productCount);
            }
        }

        $manager->persist($order);
        $manager->flush();
        
        return $this->redirectToRoute("myshop.main_page");
    }

    /**
     * @Template()
     * //@Security("has_role('ROLE_CUSTOMER')")
     */
    public function historyOrderAction()
    {
        $customer = $this->getUser();
        $orders = $this->getDoctrine()->getRepository("MyShopDefaultBundle:CustomerOrder")->findBy(["customer" => $customer]);
        return ['orders' => $orders];
    }


//    /**
//     * @Template()
//     */
//    public function orderProductsAction($id)
//    {
//        $order = $this->getDoctrine()->getRepository("MyShopDefaultBundle:CustomerOrder")->find($id);
//        if ($order == null) {
//            throw $this->createNotFoundException();
//        }
//        return ['order' => $order];
//    }

    /**
     * @Template()
    */
    public function orderProductsAction(CustomerOrder $order)
    {
        $products = $order->getProducts();

        foreach ($products as $prod)
        {
            $productShop = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($prod->getIdProduct());
            if ($productShop == null) {
                $prod->setIdProduct(null);
            }
        }

        return ['order' => $order];
    }

    public function orderRemoveProductAction(OrderProduct $orderProduct)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($orderProduct);
        $manager->flush();

        return $this->redirectToRoute("myshop.main_page");
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
