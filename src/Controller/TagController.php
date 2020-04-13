<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    /**
     * @Route("/tag/{id}/{page}", name="tag_index", requirements={"page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param Tag $tag
     * @param int $page
     * @return Response
     */
    public function showAction(ItemRepository $itemRepository, Tag $tag, $page = 1)
    {
        $items = $itemRepository->findPaginatedByTagId($tag->getId(), $page, Item::LIMIT, $this->getUser());

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('tag/index.html.twig', [
            'items' => $items,
            'tag' => $tag,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}