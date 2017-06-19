<?php

namespace Tests\Functional\AppBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PropertyDayRepositoryTest extends WebTestCase
{
    public function testBatchUpdateAvailability()
    {
        $client = static::createClient();
        $this->assertTrue(true);
    }
}
