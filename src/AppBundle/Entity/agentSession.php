<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * agentSession
 *
 * @ORM\Table(name="agent_session")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\agentSessionRepository")
 */
class agentSession {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var agent
     * @ORM\ManyToOne(targetEntity="agent", inversedBy="sessions")
     * @ORM\JoinColumn(name="agent", referencedColumnName="id",nullable=true)
     * 
     */
    private $agent;

    /**
     * @var position
     * @ORM\ManyToOne(targetEntity="position", inversedBy="sessions")
     * @ORM\JoinColumn(name="position", referencedColumnName="id",nullable=true)
     * 
     */
    private $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_open", type="boolean")
     */
    private $isOpen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="login", type="datetime")
     */
    private $login;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logout", type="datetime", nullable= true)
     */
    private $logout;

    /**
     * 
     * @ORM\OneToMany(targetEntity="turn", mappedBy="session")
     * 
     */
    private $turns;

    public function __construct() {
        $this->turns = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set login
     *
     * @param \DateTime $login
     *
     * @return agentSession
     */
    public function setLogin($login) {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return \DateTime
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Set logout
     *
     * @param \DateTime $logout
     *
     * @return agentSession
     */
    public function setLogout($logout) {
        $this->logout = $logout;

        return $this;
    }

    /**
     * Get logout
     *
     * @return \DateTime
     */
    public function getLogout() {
        return $this->logout;
    }

    /**
     * Set agent
     *
     * @param \AppBundle\Entity\agent $agent
     *
     * @return agentSession
     */
    public function setAgent(\AppBundle\Entity\agent $agent = null) {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return \AppBundle\Entity\agent
     */
    public function getAgent() {
        return $this->agent;
    }

    /**
     * Set position
     *
     * @param \AppBundle\Entity\position $position
     *
     * @return agentSession
     */
    public function setPosition(\AppBundle\Entity\position $position = null) {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \AppBundle\Entity\position
     */
    public function getPosition() {
        return $this->position;
    }

    public function getTurns() {
        return $this->turns;
    }


    /**
     * Set isOpen
     *
     * @param boolean $isOpen
     *
     * @return agentSession
     */
    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    /**
     * Get isOpen
     *
     * @return boolean
     */
    public function getIsOpen()
    {
        return $this->isOpen;
    }

    /**
     * Add turn
     *
     * @param \AppBundle\Entity\turn $turn
     *
     * @return agentSession
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
}
