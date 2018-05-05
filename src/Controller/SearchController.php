<?php

namespace App\Controller;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request)
    {
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);
        $phrase = $request->get('q');
        $items = $itemRepository->findByPhrase($phrase);
        return $this->render('default/index.html.twig', ['items' => $items]);
    }

    /**
     * Action for rendering search form
     */
    public function form()
    {
        $phrase = Request::createFromGlobals()->get('q');
        return $this->render('parts/search.html.twig', ['phrase' => $phrase]);
    }
}