<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParserCheckCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('parser:check');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['id' => 20]);

        $output = $commandTester->getDisplay();
        $this->assertContains('All count', $output);
    }
}
