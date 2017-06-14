<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Attribute
 *
 * @ORM\Table(
 *     name="attribute",
 *     uniqueConstraints={@ORM\UniqueConstraint(
 *          name="unique_attribute_value", columns={"valueId", "value"}
 *     )}
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttributeRepository")
 */
class Attribute
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
     * @ORM\Column(name="valueId", type="string", length=255)
     */
    private $valueId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var Property[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Property", inversedBy="attributes")
     */
    private $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
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
     * Set valueId
     *
     * @param string $valueId
     *
     * @return Attribute
     */
    public function setValueId($valueId)
    {
        $this->valueId = $valueId;

        return $this;
    }

    /**
     * Get valueId
     *
     * @return string
     */
    public function getValueId()
    {
        return $this->valueId;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Attribute
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add property
     *
     * @param \AppBundle\Entity\Property $property
     *
     * @return Attribute
     */
    public function addProperty(\AppBundle\Entity\Property $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * Remove property
     *
     * @param \AppBundle\Entity\Property $property
     */
    public function removeProperty(\AppBundle\Entity\Property $property)
    {
        $this->properties->removeElement($property);
    }

    /**
     * Get properties
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
