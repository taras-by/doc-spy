<?php

namespace App\Listener;

use App\Entity\User;
use App\Event\SourceItemsAddedEvent;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SourceItemsAddedListener
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
    }

    public function onSourceItemsAdded(SourceItemsAddedEvent $event)
    {
        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        $subscribers = $repository->findSourceSubscribers($event->getSource());
        foreach($subscribers as $subscriber){
            $this->notificationService->send(
                $subscriber,
                'New items added!',
                'mail/source_items_added.html.twig',
                [
                    'items' => $event->getItems(),
                    'source' => $event->getSource(),
                ]
            );
        }
    }
}