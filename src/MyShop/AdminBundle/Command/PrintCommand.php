<?php

namespace MyShop\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrintCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Hello from my command!");
        $output->writeln("Username: " . $input->getArgument("username"));
    }

    public function configure()
    {
        $this->setName("myshop:print");
        $this->setDescription("Print some text");
        $this->setHelp("For help description");

        $this->addArgument("username", InputArgument::REQUIRED, 'Username for customer');
    }
}