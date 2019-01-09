<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Source;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    /**
     * @Route("/source/{id}/{page}", name="source_index", requirements={"page"="\d+"})
     * @param $id
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id, $page = 1)
    {
        $sourceRepository = $this->getDoctrine()->getRepository(Source::class);
        $source = $sourceRepository->find($id);

        if (
            (!$this->getUser() && $source->getVisibility() != Source::VISIBILITY_PUBLIC) ||
            ($this->getUser() && $source->getVisibility() == Source::VISIBILITY_PRIVATE && $source->getCreatedBy() != $this->getUser())
        ) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        }

        $itemsRepository = $this->getDoctrine()->getRepository(Item::class);
        $items = $itemsRepository->findPaginatedBySourceId($id, $page, Item::LIMIT);

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $sourceRepository = $this->getDoctrine()->getRepository(Source::class);
        $sources = $sourceRepository->findAll();

        return $this->render('source/list.html.twig', [
            'sources' => $sources,
        ]);
    }
}