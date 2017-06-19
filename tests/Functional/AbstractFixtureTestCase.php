<?php

namespace Tests\Functional;

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

class AbstractFixtureTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $client;

    protected function loadDevFixtures()
    {
        $this->assertNotNull(self::$client, 'Create Client before fixture load');
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
    protected function loadFixtureFile(string $file)
    {
        $this->assertNotNull(self::$client, 'Create Client before fixture load');
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
