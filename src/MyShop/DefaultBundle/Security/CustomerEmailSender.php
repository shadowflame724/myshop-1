<?php

namespace MyShop\DefaultBundle\Security;


class CustomerEmailSender
{
    /**
     * @var \Swift_Mailer
    */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}