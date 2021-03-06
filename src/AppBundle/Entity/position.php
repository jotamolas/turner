<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * position
 *
 * @ORM\Table(name="position")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\positionRepository")
 */
class position {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * 
     * @ORM\OneToMany(targetEntity="turn", mappedBy="position")
     * 
     */
    private $turns;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled;

    /**
     * @var positionState
     * @ORM\ManyToOne(targetEntity="positionState")
     * @ORM\JoinColumn(name="state", referencedColumnName="id",nullable=true)
     * 
     */
    private $state;
    
    
    
    /**
     * @var turn
     * @ORM\ManyToOne(targetEntity="turn")
     * @ORM\JoinColumn(name="activeTurn", referencedColumnName="id",nullable=true)
     * 
     */
    private $activeTurn;
    
    /**
     * @var agent
     * @ORM\ManyToOne(targetEntity="agent")
     * @ORM\JoinColumn(name="activeAgent", referencedColumnName="id",nullable=true)
     * 
     */
    private $activeAgent;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="agentSession", mappedBy="position")
     * 
     */
    private $sessions;
    
    

    public function __construct() {
        $this->turns = new \ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return position
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add turn
     *
     * @param \AppBundle\Entity\turn $turn
     *
     * @return position
     */
    public function addTurn(\AppBundle\Entity\turn $turn) {
        $this->turns[] = $turn;

        return $this;
    }

    /**
     * Remove turn
     *
     * @param \AppBundle\Entity\turn $turn
     */
    public function removeTurn(\AppBundle\Entity\turn $turn) {
        $this->turns->removeElement($turn);
    }

    /**
     * Get turns
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurns() {
        return $this->turns;
    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\positionState $state
     *
     * @return position
     */
    public function setState(\AppBundle\Entity\positionState $state = null) {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\positionState
     */
    public function getState() {
        return $this->state;
    }


    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     *
     * @return position
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set activeTurn
     *
     * @param \AppBundle\Entity\turn $activeTurn
     *
     * @return position
     */
    public function setActiveTurn(\AppBundle\Entity\turn $activeTurn = null)
    {
        $this->activeTurn = $activeTurn;

        return $this;
    }

    /**
     * Get activeTurn
     *
     * @return \AppBundle\Entity\turn
     */
    public function getActiveTurn()
    {
        return $this->activeTurn;
    }

    /**
     * Set activeAgent
     *
     * @param \AppBundle\Entity\agent $activeAgent
     *
     * @return position
     */
    public function setActiveAgent(\AppBundle\Entity\agent $activeAgent = null)
    {
        $this->activeAgent = $activeAgent;

        return $this;
    }

    /**
     * Get activeAgent
     *
     * @return \AppBundle\Entity\agent
     */
    public function getActiveAgent()
    {
        return $this->activeAgent;
    }

    /**
     * Add session
     *
     * @param \AppBundle\Entity\agentSession $session
     *
     * @return position
     */
    public function addSession(\AppBundle\Entity\agentSession $session)
    {
        $this->sessions[] = $session;

        return $this;
    }

    /**
     * Remove session
     *
     * @param \AppBundle\Entity\agentSession $session
     */
    public function removeSession(\AppBundle\Entity\agentSession $session)
    {
        $this->sessions->removeElement($session);
    }

    /**
     * Get sessions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSessions()
    {
        return $this->sessions;
    }
}
