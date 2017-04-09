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
        return [];
    }

    /**
     * @Template()
     */
    public function registrationAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);


        $form->handleRequest($request);
        if ($request->isMethod("POST"))
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
