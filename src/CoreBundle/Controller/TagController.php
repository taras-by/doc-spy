<?php

namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @Route("/tag/{id}", name="tag_show")
     */
    public function showAction($id)
    {
        return new Response('Here is output of tag with id :' . $id);
    }
}