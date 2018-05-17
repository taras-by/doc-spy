<?php
namespace App\Controller;

use App\Entity\Item;
use App\Entity\Source;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends Controller
{
    /**
     * @Route("/source/{id}/{page}", name="source_index", requirements={"page"="\d+"})
     * @param $id
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id, $page = 1)
    {
        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findPaginatedBySourceId($id, $page, Item::LIMIT);

        $sourceRepository = $this->getDoctrine()->getRepository(Source::class);
        $source = $sourceRepository->find($id);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('source/index.html.twig', [
            'items' => $items,
            'source' => $source,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }
}