<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParserRunCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('parser:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--id' => 20]);

        $output = $commandTester->getDisplay();
        $this->assertContains('[done]', $output);
    }
}
