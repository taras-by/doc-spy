<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagController extends Controller
{
    /**
     * @Route("/tag/{id}/{page}", name="tag_index", requirements={"page"="\d+"})
     * @param $id
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id, $page = 1)
    {
        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findPaginatedByTagId($id, $page, Item::LIMIT);

        $tagRepository = $this->getDoctrine()->getRepository(Tag::class);
        $tag = $tagRepository->find($id);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('tag/index.html.twig', [
            'items' => $items,
            'tag' => $tag,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}