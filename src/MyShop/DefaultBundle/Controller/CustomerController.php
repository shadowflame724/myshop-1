<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Customer;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @Template()
    */
    public function loginAction()
    {
//        $customer = new Customer();
//        $customer->setEmail("igorstokolos@gmail.com")
//            ->setIsActive(true)
//            ->setFio("igor");
//        $pas = $this->get("security.password_encoder")->encodePassword($customer, "1234");
//        $customer->setPassword($pas);
//        $manager = $this->getDoctrine()->getManager();
//        $manager->persist($customer);
//        $manager->flush();
//        echo $customer->getId();
//        die();

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        if (!is_null($error)) {
            var_dump($error);
            die();
        }

        return [];
    }

    /**
     * @Template()
     */
    public function newAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $passwordHashed = $this->get('security.password_encoder')->encodePassword($customer, $customer->getPlainPassword());
            $customer->setPassword($passwordHashed);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($customer);
            $manager->flush();

            $this->addFlash("success", "Спасибо за регистрацию!");
            return $this->redirectToRoute("myshop.main_page");
        }

        return [
            'form' => $form->createView()
        ];
    }
}
