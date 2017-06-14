<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Property
 *
 * @ORM\Table(name="property")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyRepository")
 */
class Property
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
     * @var Attribute[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Attribute", inversedBy="properties")
     */
    private $attributes;

    /**
     * @var PropertyDay[]|Collection
     *
     * @ORM\OneToMany(targetEntity="PropertyDay", mappedBy="property")
     */
    private $propertyDays;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
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
     * Add attribute
     *
     * @param Attribute $attribute
     *
     * @return Property
     */
    public function addAttribute(Attribute $attribute)
    {
        $attribute->addProperty($this);
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute
     *
     * @param Attribute $attribute
     */
    public function removeAttribute(Attribute $attribute)
    {
        $this->attributes->removeElement($attribute);
    }

    /**
     * Get attributes
     *
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Add propertyDay
     *
     * @param \AppBundle\Entity\PropertyDay $propertyDay
     *
     * @return Property
     */
    public function addPropertyDay(\AppBundle\Entity\PropertyDay $propertyDay)
    {
        $this->propertyDays[] = $propertyDay;

        return $this;
    }

    /**
     * Remove propertyDay
     *
     * @param \AppBundle\Entity\PropertyDay $propertyDay
     */
    public function removePropertyDay(\AppBundle\Entity\PropertyDay $propertyDay)
    {
        $this->propertyDays->removeElement($propertyDay);
    }

    /**
     * Get propertyDays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPropertyDays()
    {
        return $this->propertyDays;
    }
}
