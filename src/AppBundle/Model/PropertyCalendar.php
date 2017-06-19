<?php

namespace AppBundle\Model;

use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyDay;
use JMS\Serializer\Annotation as JMS;

class PropertyCalendar
{
    /**
     * @var Property
     * @JMS\Groups({"property_calendar"})
     * @JMS\Type("AppBundle\Entity\Property")
     */
    private $property;

    /**
     * @var array|PropertyDay[]
     * @JMS\Groups({"property_calendar"})
     * @JMS\Type("array<string, AppBundle\Entity\PropertyDay>")
     */
    private $days;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function addDay(PropertyDay $day)
    {
        $this->days[$day->getDate()->format('Y-m-d')] = $day;
    }

    /**
     * @param int $year 4 number of year accepted by DateTime object, e.g. 2017
     * @param int $month Month number accepted by DateTime object
     */
    public function generateDays(int $year, int $month)
    {
        $date = \DateTime::createFromFormat('Y-m', $year.'-'.$month);
        $numberOfDays = (int) $date->format('t');

        for ($i = 1; $i <= $numberOfDays; $i++) {
            $dateKey = sprintf('%s-%s-%s', $date->format('Y'), $date->format('m'), $i);
            $dayDate = \DateTime::createFromFormat('Y-m-d', $dateKey);
            if (!isset($this->days[$dayDate->format('Y-m-d')])) {
                $propertyDay = new PropertyDay();
                $propertyDay->setProperty($this->property);
                $propertyDay->setDate($dayDate);
                $this->days[$dayDate->format('Y-m-d')] = $propertyDay;
            }
        }

        uksort($this->days, [$this, 'cmp']);
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function getDay(\DateTime $date)
    {
        $key = $date->format('Y-m-d');
        if (isset($this->days[$key])) {
            return $this->days[$key];
        }

        return null;
    }

    public function getProperty(): Property
    {
        return $this->property;
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function cmp($a, $b)
    {
        $aDate = new \DateTime($a);
        $bDate = new \DateTime($b);

        if ($aDate == $bDate) {
            return 0;
        }

        return ($aDate < $bDate) ? -1 : 1;
    }
}
