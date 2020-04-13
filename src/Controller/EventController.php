<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/events/{page}", name="event_index", requirements={"page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param int $page
     * @return Response
     */
    public function indexAction(ItemRepository $itemRepository, $page = 1)
    {
        $items = $itemRepository->findEventsPaginated($page, Item::LIMIT, $this->getUser());

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('event/index.html.twig', [
            'items' => $items,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}