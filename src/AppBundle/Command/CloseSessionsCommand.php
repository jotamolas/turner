<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CloseSessionsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('turner:sessions:close')
                ->setDescription('Cierra todas las Sesiones del Turnero Activas')                
                ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
       $this->getContainer()->get('session.service')->closeActiveSessions();
       $output->writeln(sprintf('Las sessiones activas fueron terminadas'));
    }

}
