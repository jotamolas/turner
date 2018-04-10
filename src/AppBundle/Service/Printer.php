<?php

namespace AppBundle\Service;

require '/var/www/html/turner/vendor/mike42/escpos-php/autoload.php';
use AppBundle\Entity\turn;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Printer {

    protected $em;
    protected $container;
    
    /**
     * 
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * 
     * @param turn $turn
     */
    public function printTicket(turn $turn) {
        
        $connector = new \Mike42\Escpos\PrintConnectors\CupsPrintConnector('TM-T20II');
        $printer = new \Mike42\Escpos\Printer($connector);
        
        
        $printer->text("holisssssss\n");
        $printer->cut();
        $printer->close();
    }

    
    /**
     * 
     * @param turn $turn
     */
    public function printAlgo(turn $turn) {
        
        $connector = new \Mike42\Escpos\PrintConnectors\CupsPrintConnector('TM-T20II');
        $printer = new \Mike42\Escpos\Printer($connector);
        
        /** @var \Symfony\Component\Asset\Packages $package */
        $package = $this->container->get('assets.packages');
        
        
        dump(dirname(__FILE__).'/rgp_logo_offset.png');

        $logo = \Mike42\Escpos\EscposImage::load(dirname(__FILE__).'/rgp_logo_offset.png');
        $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
        $printer->graphics($logo);
        $printer->feed();
        $printer->text("Sucursal Nuevo Centro Shopping\n");
        $printer->feed();
        $printer->text("Revise el siguiente turno en pantalla. Gracias\n");
        $printer->feed(2);
        $printer->setTextSize(5,5);
        $printer->text("PAR-001\n");
        $printer->feed(3);
        $printer->setTextSize(1,1);        
        $printer->text("Gracias por visitarnos.\n");
        $printer->text("Lo invitamos a conocer nuestra WEB\n");
        $printer->setEmphasis();
        $printer->text("www.redgeneralpaz.com\n");
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }
}
