<?php

namespace MyShop\DefaultBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerOrder
 *
 * @ORM\Table(name="customer_order")
 * @ORM\Entity(repositoryClass="MyShop\DefaultBundle\Repository\CustomerOrderRepository")
 */
class CustomerOrder
{
    const STATUS_DONE = 1;
    const STATUS_OPEN = 2;
    const STATUS_REJECT = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreatedAt", type="datetime")
     */
    private $dateCreatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="MyShop\DefaultBundle\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="id_customer", referencedColumnName="id")
    */
    private $customer;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyShop\DefaultBundle\Entity\OrderProduct", mappedBy="order", cascade={"all"})
    */
    private $productList;


    public function __construct()
    {
        $this->setStatus(self::STATUS_OPEN);
        $this->setDateCreatedAt(new \DateTime("now"));
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreatedAt
     *
     * @param \DateTime $dateCreatedAt
     *
     * @return CustomerOrder
     */
    public function setDateCreatedAt($dateCreatedAt)
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }

    /**
     * Get dateCreatedAt
     *
     * @return \DateTime
     */
    public function getDateCreatedAt()
    {
        return $this->dateCreatedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return CustomerOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set customer
     *
     * @param \MyShop\DefaultBundle\Entity\Customer $customer
     *
     * @return CustomerOrder
     */
    public function setCustomer(\MyShop\DefaultBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \MyShop\DefaultBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add productList
     *
     * @param \MyShop\DefaultBundle\Entity\OrderProduct $product
     *
     * @return CustomerOrder
     */
    public function addProductList(\MyShop\DefaultBundle\Entity\OrderProduct $product)
    {
        $product->setOrder($this);
        $this->productList[] = $product;

        return $this;
    }

    /**
     * Remove productList
     *
     * @param \MyShop\DefaultBundle\Entity\OrderProduct $productList
     */
    public function removeProductList(\MyShop\DefaultBundle\Entity\OrderProduct $product)
    {
        $this->productList->removeElement($product);
    }

    /**
     * Get productList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductList()
    {
        return $this->productList;
    }
}
