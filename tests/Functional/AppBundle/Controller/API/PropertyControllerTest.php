<?php

namespace Tests\Functional\AppBundle\Controller\API;

use AppBundle\Entity\Property;
use AppBundle\Model\RequestValidationErrorList;
use Doctrine\ORM\EntityManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;

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

    private function loadDevFixtures()
    {
        $registry = self::$client->getContainer()->get('doctrine');
        $registry->getConnection()->beginTransaction();

        $inputDefinition = new InputDefinition([
            new InputOption('env', 'e', InputOption::VALUE_OPTIONAL, '', 'test'),

        ]);

        $application = new Application(self::$client->getKernel());
        $application->setDefinition($inputDefinition);

        $fixtureCommand = self::$client
            ->getContainer()
            ->get('hautelook_alice.console.command.doctrine.doctrine_orm_load_data_fixtures_command');
        $input = new ArrayInput([]);
        $input->setInteractive(false);
        $fixtureCommand->setApplication($application);
        // Use ConsoleOutput to view the Fixtures command service output
//        $fixtureCommand->run($input, new ConsoleOutput());
        $fixtureCommand->run($input, new NullOutput());
    }

    /**
     * @param string $file
     */
    private function loadFixtureFile(string $file)
    {
        $registry = self::$client->getContainer()->get('doctrine');
        $registry->getConnection()->beginTransaction();

        /** @var EntityManager $em */
        $em = $registry->getManager();

        $loader = new NativeLoader();
        $objectSet = $loader->loadFile($file);

        foreach ($objectSet->getObjects() as $object) {
            $em->persist($object);
        }

        $em->flush();
    }
}
