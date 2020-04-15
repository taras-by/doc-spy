<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
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
