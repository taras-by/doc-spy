<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Source;
use App\Repository\ItemRepository;
use App\Repository\SourceRepository;
use App\Security\SourceVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    /**
     * @Route("/source/{id}/{page}", name="source_index", requirements={"page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param Source $source
     * @param int $page
     * @return Response
     */
    public function showAction(ItemRepository $itemRepository, Source $source, $page = 1)
    {
        $this->denyAccessUnlessGranted(SourceVoter::VIEW, $source);

        $items = $itemRepository->findPaginatedBySourceId($source->getId(), $page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('source/index.html.twig', [
            'items' => $items,
            'source' => $source,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/sources", name="sources_list")
     * @param SourceRepository $sourceRepository
     * @return Response
     */
    public function listAction(SourceRepository $sourceRepository)
    {
        $sources = $sourceRepository->findAll();

        return $this->render('source/list.html.twig', [
            'sources' => $sources,
        ]);
    }
}
