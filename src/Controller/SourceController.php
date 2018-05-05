<?php
namespace App\Controller;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends Controller
{
    /**
     * @Route("/source/{id}", name="source_show")
     */
    public function showAction($id)
    {
        $itemRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemRepository->findBySourceId($id);
        return $this->render('default/index.html.twig', ['items' => $items]);
    }
}