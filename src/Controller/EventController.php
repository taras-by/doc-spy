<?php

namespace App\Controller;

use App\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    /**
     * @Route("/events/{page}", name="event_index", requirements={"page"="\d+"})
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findEventsPaginated($page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('event/index.html.twig', [
            'items' => $items,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}