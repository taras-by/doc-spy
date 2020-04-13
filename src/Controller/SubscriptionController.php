<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @IsGranted({"ROLE_ADMIN", "ROLE_USER"})
     * @Route("/subscriptions", name="subscriptions")
     * @param SubscriptionRepository $subscriptionRepository
     * @return Response
     */
    public function index(SubscriptionRepository $subscriptionRepository)
    {
        $subscriptions = $subscriptionRepository->findByUser($this->getUser());

        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
