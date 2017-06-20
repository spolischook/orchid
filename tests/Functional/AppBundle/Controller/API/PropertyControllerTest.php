<?php

namespace Tests\Functional\AppBundle\Controller\API;

use AppBundle\Entity\Property;
use AppBundle\Model\PropertyCalendar;
use AppBundle\Model\RequestValidationErrorList;
use Symfony\Bundle\FrameworkBundle\Client;
use Tests\Functional\AbstractFixtureTestCase;

class PropertyControllerTest extends AbstractFixtureTestCase
{
    /**
     * @before
     */
    public function setupFixtures()
    {
        static::$client = static::createClient();
        $this->loadDevFixtures();
    }

    public function testCalendarAction()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCalendarRequestValidationErrors(array $requestParams, array $queryErrors)
    {
        self::$client->request('GET', '/properties/calendar', $requestParams);
        $response = self::$client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            'application/json',
            $response->headers->get('content-type')
        );

        /** @var RequestValidationErrorList $errors */
        $errors = self::$client->getContainer()->get('jms_serializer')->deserialize(
            $response->getContent(),
            RequestValidationErrorList::class,
            'json'
        );

        $this->assertInstanceOf(RequestValidationErrorList::class, $errors);

        foreach ($queryErrors as $field => $expectedFieldErrors) {
            $this->assertArrayHasKey($field, $errors->getQueryErrors());
            $actualFieldErrors = $errors->getQueryErrors()[$field];

            $expectedErrorsCount = count($expectedFieldErrors);
            $actualErrorsCount = count($actualFieldErrors);
            $this->assertSame($expectedErrorsCount, $actualErrorsCount);

            for ($i = 0; $i < $expectedErrorsCount; $i++) {
                $this->assertContains($expectedFieldErrors[$i], $actualFieldErrors[$i]);
            }
        }
    }

    public function testPatchDaysAttributesAction()
    {
        /** @var Property $property */
        $property = self::$client->getContainer()->get('doctrine')->getRepository(Property::class)->findOneBy([]);
        $today = new \DateTime();
        $tomorrow = new \DateTime('+1 day');
        self::$client->request(
            'PATCH',
            sprintf(
                '/properties/%s/days/attributes?dateFrom=%s&dateTo=%s',
                $property->getId(),
                $today->format('Y-m-d'),
                $tomorrow->format('Y-m-d')
            ),
            [],
            [],
            [],
            json_encode([
                'available' => 4,
                'price' => 100500,
            ])
        );

        $response = self::$client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            'application/json',
            $response->headers->get('content-type')
        );

        var_dump($response->getContent());
    }

    public function testCalendarResource()
    {
        self::$client->request('GET', '/properties/calendar', ['month' => 6, 'year' => 2017]);
        $response = self::$client->getResponse();
        $models = self::$client->getContainer()->get('jms_serializer')->deserialize(
            $response->getContent(),
            'array<'.PropertyCalendar::class.'>',
            'json'
        );

        $this->assertCount(2, $models);
        $this->assertCount(30, $models[1]->getDays());
        $this->assertArrayHasKey('2017-06-01', $models[1]->getDays());
        $this->assertArrayHasKey('2017-06-30', $models[1]->getDays());
        $this->assertArrayNotHasKey('2017-06-31', $models[1]->getDays());
    }

    public function dataProvider(): array
    {
        return [
            [
                'requestParams' => ['month' => 13, 'year' => 2017],
                'queryErrors' => [
                    'month' => ['This value should be less than or equal to 12'],
                ]
            ],
            [
                'requestParams' => ['month' => 0, 'year' => 2017],
                'queryErrors' => [
                    'month' => ['This value should be greater than or equal to 1'],
                ]
            ],
            [
                'requestParams' => ['month' => 5, 'year' => 3017],
                'queryErrors' => [
                    'year' => ['This value should be less than or equal to 2099.'],
                ]
            ],
            [
                'requestParams' => ['month' => 5, 'year' => 0],
                'queryErrors' => [
                    'year' => ['This value should be greater than or equal to 2016.'],
                ]
            ],
        ];
    }
}
