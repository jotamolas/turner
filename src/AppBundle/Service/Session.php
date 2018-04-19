<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\turn;
use AppBundle\Entity\turnLine;
use AppBundle\Entity\turnType;
use AppBundle\Entity\turnState;
use AppBundle\Entity\position;
use AppBundle\Entity\agent;
use AppBundle\Entity\agentState;
use AppBundle\Entity\agentSession;

class Session {

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
     * @param position $position
     * @param agent $agent
     * @return agentSession
     */
    public function open(position $position, agent $agent) {

        /* Se crea la sesion activa luego del logueo y de la seleccion de la posicion */

        $session = new agentSession();
        $session->setAgent($agent)
                ->setPosition($position)
                ->setLogin(new \DateTime('now'))
        ;

        $this->em->persist($session);
        $this->em->flush();
        /* pongo la posicion en ocupada */
        $position->setState($this->em->getRepository(\AppBundle\Entity\positionState::class)->findOneByDescription('busy'));

        /* marco la session como sesion activa en el agente  y marco al agente como idle*/
        $agent->setState($this->em->getRepository(\AppBundle\Entity\agentState::class)->findOneByDescription('idle'));
        $agent->setActiveSession($session);

        $this->em->flush();


        return $session;
    }

    /**
     * 
     * @param agentSession $session
     * @return agentSession
     */
    public function close(agentSession $session) {

        $session->setLogout(new \DateTime('now'));
        $session->getPosition()->setState($this->em->getRepository(\AppBundle\Entity\positionState::class)->findOneByDescription('idle'));
        
        $session->getAgent()->setActiveSession(NULL);        
        $this->em->flush();
        
        return $session;
    }
    
    /**
     * 
     * @param agentSession $session
     */
    public function getSessionTime(agentSession $session){
        
        
        $time = $session->getLogin();
        echo $timestamp = $time->format('H:i:s');
        dump($time->format('H:i:s'));
        
    }

}
