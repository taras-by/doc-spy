<?php

namespace App\Command;

use App\Entity\User;
use App\Service\NotificationService;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificationCheckCommand extends Command
{
    /**
     * @var NotificationService
     */
    private $notificationService;

    /**
     * @var RegistryInterface
     */
    private $entityManager;

    public function __construct(NotificationService $notificationService, RegistryInterface $entityManager)
    {
        $this->notificationService = $notificationService;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('notify:check')
            ->setDescription('Check Notification Service. It should send test email to administrators');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->entityManager->getRepository(User::class)->findAdmins();
        foreach($users as $user){
            $this->notificationService->send($user, 'Check Notification Service', 'mail/check.html.twig', ['date' => new \DateTime]);
            $output->writeln(sprintf('Message sent to %s<%s>', $user->getName(), $user->getEmail()));
        }

        if (count($users) == 0){
            $output->writeln('Admin users not found!');
        }
    }
}
