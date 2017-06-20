<?php

namespace AppBundle\Tests\Unit\Model;

use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyDay;
use AppBundle\Model\PropertyCalendar;
use PHPUnit\Framework\TestCase;

class PropertyCalendarTest extends TestCase
{
    /**
     * @dataProvider generateDaysProvider
     */
    public function testGenerateDays(int $year, int $month, int $dayCount)
    {
        $propertyCalendar = new PropertyCalendar(new Property());
        $propertyCalendar->generateDays($year, $month);

        $this->assertCount($dayCount, $propertyCalendar->getDays());
    }

    public function generateDaysProvider(): array
    {
        return [
            ['Year' => 2012, 'Month' => 2, 'Count of Days' => 29],
            ['Year' => 2013, 'Month' => 2, 'Count of Days' => 28],
            ['Year' => 2017, 'Month' => 6, 'Count of Days' => 30],
            ['Year' => 2017, 'Month' => 7, 'Count of Days' => 31],
        ];
    }

    /**
     * @expectedException \Psr\Log\InvalidArgumentException
     * @expectedExceptionMessage Year "-100" and month "-100" cannot be converted to date
     */
    public function testBadParameters()
    {
        $propertyCalendar = new PropertyCalendar(new Property());
        $propertyCalendar->generateDays(-100, -100);
    }

    public function testGenerateOnlyEmptyValues()
    {
        $propertyCalendar = new PropertyCalendar(new Property());
        $day = new PropertyDay();
        $day
            ->setDate(\DateTime::createFromFormat('Y-m-d', '2017-06-19'))
            ->setPrice(777)
            ->setAvailable(777)
        ;

        $propertyCalendar->addDay($day);

        $this->assertNull($propertyCalendar->getDay(new \DateTime('2017-06-18')));
        $this->assertNotNull($propertyCalendar->getDay(new \DateTime('2017-06-19')));

        $propertyCalendar->generateDays(2017, 5);

        $this->assertNull($propertyCalendar->getDay(new \DateTime('2017-06-18')));
        $this->assertNotNull($propertyCalendar->getDay(new \DateTime('2017-06-19')));
        $this->assertSame(777, $propertyCalendar->getDay(new \DateTime('2017-06-19'))->getPrice());

        $propertyCalendar->generateDays(2017, 6);

        $this->assertNotNull($propertyCalendar->getDay(new \DateTime('2017-06-18')));
        $this->assertNotNull($propertyCalendar->getDay(new \DateTime('2017-06-19')));
        $this->assertNull($propertyCalendar->getDay(new \DateTime('2017-06-18'))->getPrice());
        $this->assertSame(777, $propertyCalendar->getDay(new \DateTime('2017-06-19'))->getPrice());
    }
}
