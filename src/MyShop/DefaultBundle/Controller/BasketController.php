<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Customer;
use MyShop\DefaultBundle\Entity\CustomerOrder;
use MyShop\DefaultBundle\Entity\OrderProduct;
use MyShop\DefaultBundle\Entity\Product;
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
        $order = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:CustomerOrder")->getOrCreateOrderByCustomer($this->getUser());

        return [
            "productList" => $order->getProductList()
        ];
    }

    public function rejectOrderAction()
    {
        $order = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:CustomerOrder")->getOrCreateOrderByCustomer($this->getUser());
        $order->setStatus(CustomerOrder::STATUS_REJECT);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($order);
        $manager->flush();

        return $this->redirectToRoute("myshop.main_page");
    }

    /**
     * @Template()
    */
    public function historyAction()
    {
        $dql = 'select o from MyShopDefaultBundle:CustomerOrder o where o.customer = :customer and o.status != :status order by o.dateCreatedAt desc';
        $orders = $this->getDoctrine()->getManager()->createQuery($dql)->setParameters([
            'customer' => $this->getUser(),
            'status' => CustomerOrder::STATUS_OPEN
        ])->getResult();

        return ['orders' => $orders];
    }

    /**
     * @Template()
     */
    public function releaseOrderAction(Request $request)
    {
        $order = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:CustomerOrder")->getOrCreateOrderByCustomer($this->getUser());

        if ($request->isMethod(Request::METHOD_POST))
        {
            $manager = $this->getDoctrine()->getManager();
            $order->setStatus(CustomerOrder::STATUS_DONE);
            $manager->persist($order);
            $manager->flush();

            $this->addFlash('success', "Спасибо за заказ!");
            return $this->redirectToRoute("myshop.main_page");
        }

        return [
            'order' => $order
        ];
    }

    public function addProductToBasketAction($idProduct)
    {
        $manager = $this->getDoctrine()->getManager();
        $order = $manager->getRepository("MyShopDefaultBundle:CustomerOrder")->getOrCreateOrderByCustomer($this->getUser());

        $productShop = $manager->getRepository("MyShopDefaultBundle:Product")->find($idProduct);
        if ($productShop == null) {
            throw $this->createNotFoundException();
        }

        $dql = 'select po from MyShopDefaultBundle:OrderProduct po where po.order = :customerOrder and po.idProduct = :idProduct';
        /** @var OrderProduct $productInOrder */
        $productInOrder = $manager->createQuery($dql)->setParameters([
            'customerOrder' => $order,
            'idProduct' => $idProduct
        ])->getOneOrNullResult();

        if ($productInOrder !== null) {
            $productInOrder->setCount($productInOrder->getCount() + 1);
            $manager->persist($productInOrder);
            $manager->flush();
        }
        else {
            $productOrder = new OrderProduct();
            $productOrder->setOrder($order)
                ->setPrice($productShop->getPrice())
                ->setCount(1)
                ->setDescription($productShop->getDescription())
                ->setIdProduct($productShop->getId())
                ->setModel($productShop->getModel());

            $order->addProductList($productOrder);
            $manager->persist($order);
            $manager->flush();
        }

        return $this->redirectToRoute("myshop.main_page");
    }
}
