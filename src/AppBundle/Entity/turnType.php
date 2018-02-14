<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * turnerType
 *
 * @ORM\Table(name="turn_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\turnTypeRepository")
 */
class turnType {

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
     *
     * @var string
     * @ORM\Column(name="suffix", type="string", length=10) 
     */
    private $suffix;



    /**
     *
     * @ORM\OneToMany(targetEntity="turnLine", mappedBy="type")
     */
    private $lines;

    public function __construct() {
        $this->lines = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return turnerType
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
     * Set suffix
     *
     * @param string $suffix
     *
     * @return turnType
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Get suffix
     *
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Add range
     *
     * @param \AppBundle\Entity\turnerRange $range
     *
     * @return turnType
     */
    public function addRange(\AppBundle\Entity\turnerRange $range)
    {
        $this->ranges[] = $range;

        return $this;
    }

    /**
     * Remove range
     *
     * @param \AppBundle\Entity\turnerRange $range
     */
    public function removeRange(\AppBundle\Entity\turnerRange $range)
    {
        $this->ranges->removeElement($range);
    }

    /**
     * Get ranges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRanges()
    {
        return $this->ranges;
    }
}
