<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagController extends Controller
{
    /**
     * @Route("/tag/{id}", name="tag_show")
     */
    public function showAction($id)
    {
        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findByTagId($id);

        return $this->render('default/index.html.twig', ['items' => $items]);
    }

    public function menuTags()
    {
        $tagsRepository = $this->getDoctrine()->getRepository(Tag::class);
        $tags = $tagsRepository->findAll();

        return $this->render('parts/tags.html.twig', ['tags' => $tags]);
    }
}