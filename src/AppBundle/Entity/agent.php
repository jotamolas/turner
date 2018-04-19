<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * user
 *
 * @ORM\Table(name="agent")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\agentRepository")
 */
class agent extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=true))
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255,nullable=true))
     */
    private $lastname;

    
    /**
     * @var agentState
     * @ORM\ManyToOne(targetEntity="agentState")
     * @ORM\JoinColumn(name="state", referencedColumnName="id",nullable=true)
     * 
     */
    private $state;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="agentSession", mappedBy="agent")
     * 
     */
    private $sessions;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="turn", mappedBy="agent")
     * 
     */
    private $turns;
    
    
    /**
     *
     * @var agentSession 
     * @ORM\ManyToOne(targetEntity="agentSession")
     * @ORM\JoinColumn(name="active_session", referencedColumnName="id",nullable=true)
     * 
     */
    private $activeSession;
    

    
    public function __construct() {
        parent::__construct();
        $this->turns = new ArrayCollection();
        $this->sessions = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return user
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return user
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }



    /**
     * Add turn
     *
     * @param \AppBundle\Entity\turn $turn
     *
     * @return agent
     */
    public function addTurn(\AppBundle\Entity\turn $turn)
    {
        $this->turns[] = $turn;

        return $this;
    }

    /**
     * Remove turn
     *
     * @param \AppBundle\Entity\turn $turn
     */
    public function removeTurn(\AppBundle\Entity\turn $turn)
    {
        $this->turns->removeElement($turn);
    }

    /**
     * Get turns
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurns()
    {
        return $this->turns;
    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\agentState $state
     *
     * @return agent
     */
    public function setState(\AppBundle\Entity\agentState $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\agentState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add session
     *
     * @param \AppBundle\Entity\agentSession $session
     *
     * @return agent
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

    /**
     * Set activeSession
     *
     * @param \AppBundle\Entity\agentSession $activeSession
     *
     * @return agent
     */
    public function setActiveSession(\AppBundle\Entity\agentSession $activeSession = null)
    {
        $this->activeSession = $activeSession;

        return $this;
    }

    /**
     * Get activeSession
     *
     * @return \AppBundle\Entity\agentSession
     */
    public function getActiveSession()
    {
        return $this->activeSession;
    }
}
