<?php

namespace AppBundle\DataFixtures\Provider;

class DayDateProvider
{
    public function todayPlusDay($number): \DateTime
    {
        return new \DateTime('+ '.$number.' days');
    }
}
