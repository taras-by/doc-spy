<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParserCheckUrlCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('parser:check:url');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['parser_service' => 'hh_ru_parser', 'url' => 'https://hh.ru/articles']);

        $output = $commandTester->getDisplay();
        $this->assertContains('All count', $output);
    }
}
