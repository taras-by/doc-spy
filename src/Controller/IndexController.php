<?php

namespace App\Controller;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"})
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findPaginatedFromFavoriteSources($page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('index/index.html.twig', [
            'items' => $items,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }

    /**
     * @Route("/all/{page}", name="all", requirements={"page"="\d+"})
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allAction($page = 1)
    {
        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findAllPaginated($page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('index/all.html.twig', [
            'items' => $items,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}
