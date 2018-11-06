<?php

namespace App\Listener;

use App\Entity\User;
use App\Event\ItemsAddedEvent;
use App\Service\NotificationService;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ItemsAddedListener
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

    public function onItemsAdded(ItemsAddedEvent $event){
        $subscribers = $this->entityManager->getRepository(User::class)->findAdmins();
        foreach($subscribers as $subscriber){
            $this->notificationService->send($subscriber, 'New items added!', 'mail/items_added.html.twig', ['items' => $event->getItems()]);
        }
    }
}