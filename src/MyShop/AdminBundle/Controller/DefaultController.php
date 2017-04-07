<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{

    public function testAction(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $response = new JsonResponse([
                "name" => "Igor",
                "time" => time()
            ]);

            return $response;
        }

        return new Response("You are not ajax!");
    }

    /**
     * @Template()
     */
    public function indexAction()
    {
        $res = $this->get('translator')->trans('hi');

//        var_dump($res);
//        die();

        return [];
    }

    public function uploadImageAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get("upload");

        $dir = $this->get("kernel")->getRootDir() . "/../web/photos/";
        $file->move($dir, $file->getClientOriginalName());

        return new Response("/photos/" . $file->getClientOriginalName());
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


