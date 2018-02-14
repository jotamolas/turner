<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * turner
 *
 * @ORM\Table(name="turn")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\turnRepository")
 */
class turn
{
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
     * @ORM\Column(name="number", type="string", length=255)
     */
    private $number;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var turnLine
     *
     * @ORM\ManyToOne(targetEntity="turnLine", inversedBy="turns")
     * @ORM\JoinColumn(name="line", referencedColumnName="id")
     */
    private $line;

    /**
     * @var agent
     *
     * @ORM\ManyToOne(targetEntity="agent", inversedBy="turns" )
     * @ORM\JoinColumn(name="agent", referencedColumnName="id",nullable=true)
     */
    private $agent;
    
    
    /**
     * @var position
     *
     * @ORM\ManyToOne(targetEntity="position", inversedBy="turns" )
     * @ORM\JoinColumn(name="position", referencedColumnName="id",nullable=true)
     */
    private $position;
    
    /**
     * @var turnState
     *
     * @ORM\ManyToOne(targetEntity="turnState")
     * @ORM\JoinColumn(name="state", referencedColumnName="id")
     */
    private $state;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;  
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time")
     */
    private $time;

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
     * Set line
     *
     * @param \AppBundle\Entity\turnLine $line
     *
     * @return turn
     */
    public function setLine(\AppBundle\Entity\turnLine $line = null)
    {
        $this->line = $line;

        return $this;
    }

    /**
     * Get line
     *
     * @return \AppBundle\Entity\turnLine
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Set agent
     *
     * @param \AppBundle\Entity\agent $agent
     *
     * @return turn
     */
    public function setAgent(\AppBundle\Entity\agent $agent = null)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return \AppBundle\Entity\agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return turn
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return turn
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\turnState $state
     *
     * @return turn
     */
    public function setState(\AppBundle\Entity\turnState $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\turnState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set position
     *
     * @param \AppBundle\Entity\position $position
     *
     * @return turn
     */
    public function setPosition(\AppBundle\Entity\position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \AppBundle\Entity\position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return turn
     */
    public function setNumber($number)
    {
        $this->number = $number;
        $this->setLabel();

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set label
     * @return turn
     */
    public function setLabel()
    {
                
        $this->label = $this->getLine()->getType()->getSuffix().'-'.$this->getNumber();
        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
