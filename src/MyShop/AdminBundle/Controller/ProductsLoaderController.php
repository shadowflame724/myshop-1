<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsLoaderController extends Controller
{
    public function exportProductsAction()
    {
        $csvData = $this->get("myshop_admin.product_import_export")->exportProducts();

        $response = new Response($csvData);

        $response->headers->set("Content-disposition", "attachment;filename=products_".date("d.m.Y_H:i:s").".csv");

        return $response;
    }

    /**
     * Загружаем товары
     *
     * @Template()
    */
    public function importProductsAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('csv_file', FileType::class, ['label' => 'Выберите CSV файл с товарами:'])
            ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod("POST"))
        {
            $data = $form->getData();
            /** @var UploadedFile $csvFile */
            $csvFile = $data['csv_file'];



            $this->get("myshop_admin.product_import_export")->parseCsvData($csvFile->getRealPath());

            $this->addFlash("success", "Данные успешно добавлены в базу!");
        }

        return ['form' => $form->createView()];
    }
}