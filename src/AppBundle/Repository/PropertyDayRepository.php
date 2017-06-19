<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PropertyDayRepository extends EntityRepository
{
    public function batchUpdateAvailability(array $ids, int $available, \DatePeriod $datePeriod)
    {

    }
}
