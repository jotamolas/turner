<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * turnerRange
 *
 * @ORM\Table(name="turn_line")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\turnLineRepository")
 */
class turnLine {

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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var turnerType
     *
     * @ORM\ManyToOne(targetEntity="turnType", inversedBy="lines")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;    

    /**
     * @ORM\OneToMany(targetEntity="turn", mappedBy="line")
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
     * Set description
     *
     * @param string $description
     *
     * @return turnerRange
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return turnerRange
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }






    /**
     * Set type
     *
     * @param \AppBundle\Entity\turnType $type
     *
     * @return turnLine
     */
    public function setType(\AppBundle\Entity\turnType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\turnType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add turn
     *
     * @param \AppBundle\Entity\turn $turn
     *
     * @return turnLine
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
}
