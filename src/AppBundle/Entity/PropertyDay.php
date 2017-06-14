<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PropertyDay
 *
 * @ORM\Table(name="property_day")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyDayRepository")
 */
class PropertyDay
{
    const MONDAY    = 'Mon';
    const TUESDAY   = 'Tue';
    const WEDNESDAY = 'Wed';
    const THURSDAY  = 'Thu';
    const FRIDAY    = 'Fri';
    const SATURDAY  = 'Sat';
    const SUNDAY    = 'Sun';

    private $weekdays = [
        self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY
    ];

    private $weekends = [self::SATURDAY, self::SUNDAY];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="propertyDays")
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="isWeekday", type="boolean")
     */
    private $isWeekday;

    /**
     * @var bool
     *
     * @ORM\Column(name="isWeekend", type="boolean")
     */
    private $isWeekend;

    /**
     * @var string
     *
     * @ORM\Column(name="dayOfTheWeek", type="string", length=3)
     */
    private $dayOfTheWeek;

    /**
     * @var bool
     *
     * @ORM\Column(name="isBooked", type="boolean")
     */
    private $isBooked;

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
     * Set property
     *
     * @param Property $property
     *
     * @return PropertyDay
     */
    public function setProperty(Property $property = null): PropertyDay
    {
        $this->property = $property;
        $property->addPropertyDay($this);

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty(): Property
    {
        return $this->property;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return PropertyDay
     */
    public function setPrice($price): PropertyDay
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return PropertyDay
     */
    public function setDate(\DateTime $date): PropertyDay
    {
        $this->date = $date;
        $this->dayOfTheWeek = $date->format('D');
        $this->isWeekday = in_array($this->dayOfTheWeek, $this->getWeekdays(), true);
        $this->isWeekend = in_array($this->dayOfTheWeek, $this->getWeekends(), true);

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * A textual representation of a day, three letters, e.g. 'Mon'
     *
     * @return string
     */
    public function getDayOfTheWeek(): string
    {
        return $this->dayOfTheWeek;
    }

    /**
     * Get isWeekday
     *
     * @return bool
     */
    public function isWeekday(): bool
    {
        return $this->isWeekday;
    }

    /**
     * Get isWeekend
     *
     * @return bool
     */
    public function isWeekend(): bool
    {
        return $this->isWeekend;
    }

    /**
     * Return array of the weekends days, e.g. ['Sun', 'Sat']
     * @return array
     */
    public function getWeekends(): array
    {
        return $this->weekends;
    }

    /**
     * Return array of the weekdays, e.g. ['Mon', 'Thu']
     * @return array
     */
    public function getWeekdays(): array
    {
        return $this->weekdays;
    }

    /**
     * Set isBooked
     *
     * @param boolean $isBooked
     *
     * @return PropertyDay
     */
    public function setIsBooked($isBooked)
    {
        $this->isBooked = $isBooked;

        return $this;
    }

    /**
     * Get isBooked
     *
     * @return bool
     */
    public function isBooked(): bool
    {
        return $this->isBooked;
    }
}
