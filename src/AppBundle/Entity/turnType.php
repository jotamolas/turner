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
     * @var boolean
     * @ORM\Column(name="is_enabled", type="boolean") 
     */
    private $isEnabled;
    
    /**
     *
     * @var string
     * @ORM\Column(name="panel_color", type="string", length=7) 
     */
    private $panelColor;

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
     * Add line
     *
     * @param \AppBundle\Entity\turnLine $line
     *
     * @return turnType
     */
    public function addLine(\AppBundle\Entity\turnLine $line)
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * Remove line
     *
     * @param \AppBundle\Entity\turnLine $line
     */
    public function removeLine(\AppBundle\Entity\turnLine $line)
    {
        $this->lines->removeElement($line);
    }

    /**
     * Get lines
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     *
     * @return turnType
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
     * Set panelColor
     *
     * @param string $panelColor
     *
     * @return turnType
     */
    public function setPanelColor($panelColor)
    {
        $this->panelColor = $panelColor;

        return $this;
    }

    /**
     * Get panelColor
     *
     * @return string
     */
    public function getPanelColor()
    {
        return $this->panelColor;
    }
}
