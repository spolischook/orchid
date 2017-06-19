<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * PropertyDay
 *
 * @ORM\Table(
 *     name="property_day",
 *     uniqueConstraints={@ORM\UniqueConstraint(
 *          name="property_day", columns={"property_id", "date"}
 *     )}
 * )
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

    /**
     * @var array
     * @JMS\Exclude
     */
    private $weekdays = [
        self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY
    ];

    /**
     * @var array
     * @JMS\Exclude
     */
    private $weekends = [self::SATURDAY, self::SUNDAY];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Exclude
     */
    private $id;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="propertyDays")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="integer")
     * @JMS\Groups({"property_list"})
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="available", type="integer")
     * @JMS\Groups({"property_list"})
     */
    private $available;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @JMS\Groups({"property_list"})
     * @JMS\Type("DateTime<'Y-m-d'>")
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
     * @var int
     *
     * @ORM\Column(name="month", type="smallint")
     */
    private $month;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="smallint")
     */
    private $year;

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
    public function setProperty(Property $property): PropertyDay
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
        $this->month = $date->format('m');
        $this->year = $date->format('Y');

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
     * @param int $available
     *
     * @return PropertyDay
     */
    public function setAvailable(int $available): PropertyDay
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return int
     */
    public function getAvailable(): int
    {
        return $this->available;
    }

    /**
     * @param int $month
     *
     * @return PropertyDay
     */
    public function setMonth(int $month): PropertyDay
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @param int $year
     *
     * @return PropertyDay
     */
    public function setYear(int $year): PropertyDay
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }
}
