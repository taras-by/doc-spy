<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ItemsController extends Controller
{
    /**
     * @Route("/items")
     */
    public function indexAction()
    {
        return new JsonResponse(['ok','200']);
    }
}
