<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\position;


class PositionService{
    
    
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
     */
    public function clean(position $position){
        $position->setActiveAgent(NULL)->setActiveTurn(NULL);
        return $position;
    }
    
}
