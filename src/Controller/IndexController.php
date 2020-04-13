<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param int $page
     * @return Response
     */
    public function indexAction(ItemRepository $itemRepository, $page = 1)
    {
        $items = $itemRepository->findMainPaginated($page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('index/index.html.twig', [
            'items' => $items,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("/feed/{page}", name="feed", requirements={"page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param int $page
     * @return Response
     */
    public function feedAction(ItemRepository $itemRepository, $page = 1)
    {
        $user = $this->getUser();
        $items = $itemRepository->findUserFeedPaginated($user, $page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('index/feed.html.twig', [
            'items' => $items,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}
