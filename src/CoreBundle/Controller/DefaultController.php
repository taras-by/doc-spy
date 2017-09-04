<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\Item;
use CoreBundle\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        /** @var $itemsRepository ItemRepository */
        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findLast();
        return $this->render('CoreBundle:Default:index.html.twig', ['items' => $items]);
    }
}
