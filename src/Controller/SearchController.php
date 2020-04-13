<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search/{page}", name="search", requirements={"page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function indexAction(ItemRepository $itemRepository, Request $request, $page = 1)
    {
        $phrase = $request->get('q');

        $items = $itemRepository->findPaginatedByPhrase($phrase, $page, Item::LIMIT, $this->getUser());

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('search/index.html.twig', [
            'items' => $items,
            'phrase' => $phrase,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}