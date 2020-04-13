<?php

namespace App\Controller;

use App\Entity\Subscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/subscriptions", name="subscriptions")
     */
    public function index()
    {
        $subscriptionRepository = $this->getDoctrine()->getRepository(Subscription::class);
        $subscriptions = $subscriptionRepository->findAll();

        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
