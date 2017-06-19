<?php

namespace AppBundle\Tests\Unit\Entity;

use AppBundle\Entity\PropertyDay;
use PHPUnit\Framework\TestCase;

class PropertyDayTest extends TestCase
{
    /**
     * @dataProvider setDateProvider
     * @param string $date
     * @param string $DotW
     * @param bool $isWeekend
     * @param bool $isWeekday
     * @param int $year
     * @param int $month
     */
    public function testSetDate(string $date, string $DotW, bool $isWeekend, bool $isWeekday, int $year, int $month)
    {
        $propertyDay = new PropertyDay();
        $propertyDay->setDate(\DateTime::createFromFormat('Y-m-d', $date));

        $this->assertSame($DotW, $propertyDay->getDayOfTheWeek());
        $this->assertSame($isWeekend, $propertyDay->isWeekend());
        $this->assertSame($isWeekday, $propertyDay->isWeekday());
        $this->assertSame($year, $propertyDay->getYear());
        $this->assertSame($month, $propertyDay->getMonth());
    }

    public function setDateProvider()
    {
        return [
            ['2017-06-12', 'Mon', 'isWeekend' => false, 'isWeekday' => true, 2017, 06],
            ['2017-06-13', 'Tue', 'isWeekend' => false, 'isWeekday' => true, 2017, 06],
            ['2017-06-14', 'Wed', 'isWeekend' => false, 'isWeekday' => true, 2017, 06],
            ['2017-06-15', 'Thu', 'isWeekend' => false, 'isWeekday' => true, 2017, 06],
            ['2017-06-16', 'Fri', 'isWeekend' => false, 'isWeekday' => true, 2017, 06],
            ['2017-06-17', 'Sat', 'isWeekend' => true, 'isWeekday' => false, 2017, 06],
            ['2017-06-18', 'Sun', 'isWeekend' => true, 'isWeekday' => false, 2017, 06],
            ['2018-07-20', 'Fri', 'isWeekend' => false, 'isWeekday' => true, 2018, 07],
            ['2018-07-21', 'Sat', 'isWeekend' => true, 'isWeekday' => false, 2018, 07],
        ];
    }
}
