<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\Item;
use CoreBundle\Entity\Tag;
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

        return $this->render('CoreBundle:Default:index.html.twig', ['items' => $items]);
    }

    public function menuTagsAction()
    {
        $tagsRepository = $this->getDoctrine()->getRepository(Tag::class);
        $tags = $tagsRepository->findAll();

        return $this->render('CoreBundle:Parts:tags.html.twig', ['tags' => $tags]);
    }
}