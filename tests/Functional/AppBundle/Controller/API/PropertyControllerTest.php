<?php

namespace Tests\Functional\AppBundle\Controller\API;

use AppBundle\Entity\Property;
use Doctrine\ORM\EntityManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PropertyControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @before
     */
    public function setupFixtures()
    {
        self::$client = static::createClient();
        $registry = self::$client->getContainer()->get('doctrine');
        $registry->getConnection()->beginTransaction();
        /** @var EntityManager $em */
        $em = $registry->getManager();

        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/data/properties.yml');

        foreach ($objectSet->getObjects() as $object) {
            $em->persist($object);
        }

        $em->flush();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCGet()
    {
        self::$client->request('GET', '/properties');

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
        $this->assertEquals(
            'application/json',
            self::$client->getResponse()->headers->get('content-type')
        );

        dump(self::$client->getContainer()->get('jms_serializer')->deserialize(
            self::$client->getResponse()->getContent(),
            Property::class,
            'json'
        ));
    }

    public function dataProvider(): array
    {
        return [
            [],
            [],
        ];
    }
}
