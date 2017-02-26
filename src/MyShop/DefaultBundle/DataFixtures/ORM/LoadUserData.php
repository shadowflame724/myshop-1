<?php

namespace MyShop\DefaultBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyShop\AdminBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++)
        {
            $user = new User();
            $user->setEmail(rand() . '@gmail.com');
            $user->setUsername(rand());
            $pas = rand(1000000, 99999999);
            $pasHash = $this->container->get('security.password_encoder')->encodePassword($user, $pas);
            $user->setPassword($pasHash);

            $manager->persist($user);
            $manager->flush();
        }
    }
}