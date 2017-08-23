<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ItemsController extends Controller
{
    /**
     * @Route("/items")
     */
    public function indexAction()
    {

        $items = $this->getDoctrine()->getRepository(Item::class)->findAll();

        $serializer = new Serializer(
            [new GetSetMethodNormalizer()],
            ['json' => new JsonEncoder()]
        );

        $json = $serializer->serialize($items, 'json');

        return new Response($json);
    }
}
