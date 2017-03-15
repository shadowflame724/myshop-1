<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template()
    */
    public function indexAction()
    {
        return [];
    }

    public function loadUsersAction()
    {
        set_time_limit(0);
        ignore_user_abort();

        $this->get("load_my_pre_data")->loadUsers();
        $this->addFlash("success", "Демо пользователи загружены!");

        return $this->redirectToRoute("my_shop_admin.index");
    }
}
