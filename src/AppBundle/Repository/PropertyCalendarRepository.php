<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PropertyDay;
use AppBundle\Model\PropertyCalendar;

class PropertyCalendarRepository
{
    /**
     * @var PropertyRepository
     */
    private $propertyRepository;

    /**
     * @var PropertyDayRepository
     */
    private $dayRepository;

    public function __construct(PropertyRepository $propertyRepository, PropertyDayRepository $dayRepository)
    {
        $this->propertyRepository = $propertyRepository;
        $this->dayRepository = $dayRepository;
    }

    /**
     * @param int $year
     * @param int $month
     * @return array|PropertyCalendar[]
     */
    public function getAllByYearAndMonth(int $year, int $month): array
    {
        $properties = $this->propertyRepository->findAll();
        $calendar = [];

        foreach ($properties as $property) {
            $model = new PropertyCalendar($property);
            $days = $this->dayRepository->findBy([
                'month' => $month,
                'year' => $year,
                'property' => $property
            ]);

            /** @var PropertyDay $day */
            foreach ($days as $day) {
                $model->addDay($day);
            }

            $model->generateDays($year, $month);
            $calendar[] = $model;
        }

        return $calendar;
    }

    public function patch(PropertyCalendar $propertyCalendar)
    {
    }
}
