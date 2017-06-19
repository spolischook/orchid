<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

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
     * @JMS\Groups({"property_list"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="inventory", type="integer")
     */
    private $inventory;

    /**
     * @var PropertyDay[]|Collection
     *
     * @ORM\OneToMany(targetEntity="PropertyDay", mappedBy="property")
     * @JMS\Groups({"property_list"})
     */
    private $propertyDays;

    public function __construct()
    {
        $this->propertyDays = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return Property
     */
    public function setTitle(string $title): Property
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param int $inventory
     *
     * @return Property
     */
    public function setInventory(int $inventory): Property
    {
        $this->inventory = $inventory;
        return $this;
    }

    /**
     * @return int
     */
    public function getInventory(): int
    {
        return $this->inventory;
    }

    /**
     * Add propertyDay
     *
     * @param PropertyDay $propertyDay
     *
     * @return Property
     */
    public function addPropertyDay(PropertyDay $propertyDay): Property
    {
        $this->propertyDays[] = $propertyDay;

        return $this;
    }

    /**
     * Remove propertyDay
     *
     * @param PropertyDay $propertyDay
     */
    public function removePropertyDay(PropertyDay $propertyDay)
    {
        $this->propertyDays->removeElement($propertyDay);
    }

    /**
     * Get propertyDays
     *
     * @return Collection|PropertyDay[]
     */
    public function getPropertyDays(): Collection
    {
        return $this->propertyDays;
    }
}
