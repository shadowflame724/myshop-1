<?php

namespace MyShop\AdminBundle\Utils;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use MyShop\AdminBundle\Event\ProductAddEvent;
use MyShop\DefaultBundle\Entity\Product;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductImportExport
{
    /**
     * @var EntityManager
    */
    private $manager;

    private $logger;

    private $eventDispatcher;

    public function __construct(EntityManagerInterface $manager, EventDispatcherInterface $eventDispatcher)
    {
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function exportProducts()
    {
        $products = $this->manager->createQuery("select p from MyShopDefaultBundle:Product p")->getResult();

        $csv = "model,description,price" . "\n";

        /** @var Product $product */
        foreach ($products as $product)
        {
            $csv .= $product->getModel() . "," . $product->getDescription() . "," . $product->getPrice() . "\n";
        }

        return $csv;
    }

    public function parseCsvData($filePath, $clearProducts = false)
    {
        ini_set("auto_detect_line_endings", true);

        $fh = fopen($filePath, "r");
        if ($fh == null) {
            throw new \Exception("Can't open file!");
        }

        // $rows = array_map('str_getcsv', file('myfile.csv'));

        set_time_limit(0);

//        if ($clearProducts == true)
//        {
//            $this->manager->getConnection()->exec("SET foreign_key_checks = 0");
//            $this->manager->getConnection()->exec("truncate product");
//        }

        $this->manager->beginTransaction();

        try {
            fgetcsv($fh);
            while ( ($data = fgetcsv($fh)) != FALSE )
            {
                if ($data[0] !== "" and $data[1] !== "" and $data[2] !== "") {
                    $product = new Product();
                    $product->setModel($data[0]);
                    $product->setDescription($data[1]);
                    $product->setPrice($data[2]);

                    $this->manager->persist($product);
                    $this->manager->flush();

                    $event = new ProductAddEvent($product);
                    $this->eventDispatcher->dispatch("product_add_event", $event);
                }
            }

            $this->manager->commit();

        } catch (\Exception $ex) {
            $this->manager->rollback();
        }

        fclose($fh);
    }
}