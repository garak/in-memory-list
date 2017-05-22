<?php

use InMemoryList\Command\FlushCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class FlushCommandTest extends TestCase
{
    /**
     * @var Application
     */
    private $app;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->app = new Application();
        $this->app->add(new FlushCommand());
    }

    /**
     * @test
     */
    public function it_displays_correctly_memcached_flush_message()
    {
        $command = $this->app->find('iml:cache:flush');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'driver' => 'memcached',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('[memcached] Cache was successful flushed.', $output);
    }

    /**
     * @test
     */
    public function it_displays_correctly_apcu_flush_message()
    {
        $command = $this->app->find('iml:cache:flush');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'driver' => 'apcu',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('[apcu] Cache was successful flushed.', $output);
    }

    /**
     * @test
     */
    public function it_displays_correctly_redis_flush_message()
    {
        $command = $this->app->find('iml:cache:flush');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'driver' => 'redis',
        ]);

        $output = $commandTester->getDisplay();

        $this->assertContains('[redis] Cache was successful flushed.', $output);
    }
}