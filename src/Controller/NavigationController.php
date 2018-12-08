<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NavigationController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navbar()
    {
        /** @var TagRepository $tagsRepository */
        $tagsRepository = $this->getDoctrine()->getRepository(Tag::class);
        $tags = $tagsRepository->findFavorites();

        $phrase = Request::createFromGlobals()->get('q');

        return $this->render('parts/navbar.html.twig', [
            'tags' => $tags,
            'phrase' => $phrase,
            'user' => $this->getUser(),
        ]);
    }
}