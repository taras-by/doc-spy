<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\NotificationService;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * NotificationCheckCommand constructor.
     * @param NotificationService $notificationService
     * @param UserRepository $userRepository
     */
    public function __construct(NotificationService $notificationService, UserRepository $userRepository)
    {
        $this->notificationService = $notificationService;
        $this->userRepository = $userRepository;
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
        $users = $this->userRepository->findAdmins();
        foreach ($users as $user) {
            $this->notificationService->send($user, 'Check Notification Service', 'mail/check.html.twig', ['date' => new \DateTime]);
            $output->writeln(sprintf('Message sent to %s<%s>', $user->getName(), $user->getEmail()));
        }

        if (count($users) == 0) {
            $output->writeln('Admin users not found!');
        }

        return 0;
    }
}
