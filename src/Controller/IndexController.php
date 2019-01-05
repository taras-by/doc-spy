<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"})
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1)
    {
        $items = $this->getItemRepository()->findAllPaginated($page, Item::LIMIT);

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
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function feedAction($page = 1)
    {
        $user = $this->getUser();
        $items = $this->getItemRepository()->findUserFeedPaginated($user, $page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('index/feed.html.twig', [
            'items' => $items,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }

    private function getItemRepository(): ItemRepository
    {
        return $this->getDoctrine()->getRepository(Item::class);
    }
}
