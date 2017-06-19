<?php

namespace AppBundle\DataFixtures\Faker\Provider;

class DayDateProvider
{
    public function todayPlusDay($number): \DateTime
    {
        return new \DateTime('+ '.$number.' days');
    }
}
