<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificationCheckCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('notify:check')
            ->setDescription('Check Notification Service. It should send test email to administrators');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get('notification_service');
        $users = $this->getContainer()->get('doctrine')->getRepository(User::class)->findAdmins();
        foreach($users as $user){
            $service->send($user, 'Check Notification Service', 'mail/check.html.twig', ['date' => new \DateTime]);    
            $output->writeln(sprintf('Message sent to %s<%s>', $user->getName(), $user->getEmail()));
        }
    }
}
