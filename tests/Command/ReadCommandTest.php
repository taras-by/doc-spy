<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ReadCommandTest extends KernelTestCase
{

    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('read');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--results' => 3]);

        $output = $commandTester->getDisplay();
        $this->assertContains("----------\n", $output);
    }
}
