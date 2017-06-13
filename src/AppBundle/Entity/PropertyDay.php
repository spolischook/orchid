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
     * @ORM\Column(name="price", type="decimal", precision=2, scale=0, nullable=true)
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
     * @ORM\Column(name="dayOfTheWeek", type="string", length=255)
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return PropertyDay
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
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
     * Set isWeekday
     *
     * @param boolean $isWeekday
     *
     * @return PropertyDay
     */
    public function setIsWeekday($isWeekday)
    {
        $this->isWeekday = $isWeekday;

        return $this;
    }

    /**
     * Get isWeekday
     *
     * @return bool
     */
    public function getIsWeekday()
    {
        return $this->isWeekday;
    }

    /**
     * Set isWeekend
     *
     * @param boolean $isWeekend
     *
     * @return PropertyDay
     */
    public function setIsWeekend($isWeekend)
    {
        $this->isWeekend = $isWeekend;

        return $this;
    }

    /**
     * Get isWeekend
     *
     * @return bool
     */
    public function isWeekend()
    {
        return $this->isWeekend;
    }

    /**
     * Set dayOfTheWeek
     *
     * @param string $dayOfTheWeek
     *
     * @return PropertyDay
     */
    public function setDayOfTheWeek($dayOfTheWeek)
    {
        $this->dayOfTheWeek = $dayOfTheWeek;

        return $this;
    }

    /**
     * Get dayOfTheWeek
     *
     * @return string
     */
    public function getDayOfTheWeek()
    {
        return $this->dayOfTheWeek;
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
    public function isBooked()
    {
        return $this->isBooked;
    }
}
