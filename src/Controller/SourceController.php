<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Source;
use App\Entity\User;
use App\Form\SourceType;
use App\Helper\ParserStatistics;
use App\Repository\ItemRepository;
use App\Repository\SourceRepository;
use App\Security\SourceVoter;
use App\Service\ParserHandler;
use App\Service\ParserManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    /**
     * @Route("/source/{id}/{page}", name="source_items", methods={"GET"}, requirements={"id"="\d+", "page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param Source $source
     * @param int $page
     * @return Response
     */
    public function items(ItemRepository $itemRepository, Source $source, $page = 1)
    {
        $this->denyAccessUnlessGranted(SourceVoter::VIEW, $source);

        $items = $itemRepository->findPaginatedBySourceId($source->getId(), $page, Item::LIMIT);

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('source/items.html.twig', [
            'items' => $items,
            'source' => $source,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/sources", name="source_list")
     * @param SourceRepository $sourceRepository
     * @return Response
     */
    public function list(SourceRepository $sourceRepository)
    {
        $sources = $sourceRepository->findAll();

        return $this->render('source/list.html.twig', [
            'sources' => $sources,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/new", name="source_new", methods={"GET","POST"})
     * @param UrlHelper $urlHelper
     * @param Request $request
     * @return Response
     */
    public function new(UrlHelper $urlHelper, Request $request): Response
    {
        $source = (new Source())
            ->setIcon($urlHelper->getAbsoluteUrl('/favicon.ico'));
        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var  User $user */
            $user = $this->getUser();
            $source->setCreatedBy($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($source);
            $entityManager->flush();

            return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
        }

        return $this->render('source/new.html.twig', [
            'source' => $source,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/{id}/show", name="source_show", methods={"GET"})
     * @param ItemRepository $itemRepository
     * @param Source $source
     * @return Response
     * @throws Exception
     */
    public function show(ItemRepository $itemRepository, Source $source): Response
    {
        return $this->render('source/show.html.twig', [
            'source' => $source,
            'itemsCount' => $itemRepository->getCountBySourceId($source->getId()),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/{id}/edit", name="source_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Source $source
     * @return Response
     */
    public function edit(Request $request, Source $source): Response
    {
        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', sprintf('Source saved'));
            return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
        }

        return $this->render('source/edit.html.twig', [
            'source' => $source,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/{id}", name="source_delete", methods={"DELETE"})
     * @param Request $request
     * @param Source $source
     * @return Response
     */
    public function delete(Request $request, Source $source): Response
    {
        if ($this->isCsrfTokenValid('delete' . $source->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($source);
            $entityManager->flush();
            $this->addFlash('success', sprintf('Source "%s" deleted', $source->getName()));
        }

        return $this->redirectToRoute('source_list');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/{id}/clean", name="source_clean", methods={"DELETE"})
     * @param ItemRepository $itemRepository
     * @param Request $request
     * @param Source $source
     * @return Response
     */
    public function clean(ItemRepository $itemRepository, Request $request, Source $source): Response
    {
        if ($this->isCsrfTokenValid('clean' . $source->getId(), $request->request->get('_token'))) {
            $countDeletedItems = $itemRepository->deleteBySource($source);
            $this->addFlash('success', sprintf('%d source items deleted', $countDeletedItems));
        }

        return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/{id}/update", name="source_update", methods={"POST"})
     * @param ParserManager $parserManager
     * @param ParserHandler $parserHandler
     * @param Request $request
     * @param Source $source
     * @return Response
     * @throws Exception
     */
    public function update(ParserManager $parserManager, ParserHandler $parserHandler, Request $request, Source $source): Response
    {
        if ($this->isCsrfTokenValid('update' . $source->getId(), $request->request->get('_token'))) {
            $parser = $parserManager->getParser($source);
            $parser->run();

            if ($parser->hasErrors()) {
                $this->addFlash('danger', sprintf('Update error'));
            } else {
                $parserHandler->handle($parser);
                $newItemsCount = $parserHandler->getSavedCount();
                $this->addFlash('success', sprintf('Source updated. %d items added', $newItemsCount));
            }
        }

        return $this->redirectToRoute('source_show', ['id' => $source->getId()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/{id}/check", name="source_check", methods={"GET"})
     * @param ParserManager $parserManager
     * @param Request $request
     * @param Source $source
     * @return Response
     */
    public function check(ParserManager $parserManager, Request $request, Source $source): Response
    {
        $title = sprintf('Check source "%s"', $source->getName());
        $body = $this->processParserCheck($parserManager, $source);

        if ($request->isXmlHttpRequest()) {
            return new Response(json_encode([
                'body' => $body,
                'title' => $title,
            ]));
        }
        return $this->render('source/check.html.twig', [
            'body' => $body,
            'title' => $title,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/source/form/check", name="source_form_check", methods={"POST"})
     * @param ParserManager $parserManager
     * @param Request $request
     * @return Response
     */
    public function formCheck(ParserManager $parserManager, Request $request): Response
    {
        $source = new Source();
        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $title = sprintf('Check source "%s"', $source->getName());
            $body = $this->processParserCheck($parserManager, $source);

            return new Response(json_encode([
                'body' => $body,
                'title' => $title,
            ]));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return new Response(json_encode([
            'errors' => $errors,
        ]));
    }

    /**
     * @param ParserManager $parserManager
     * @param Source $source
     * @return string
     */
    protected function processParserCheck(ParserManager $parserManager, Source $source)
    {
        try {
            $parser = $parserManager->getParser($source);
            $parser->run();
        } catch (Exception $e) {
            return $this->renderView('source/_check_error.html.twig', [
                'message' => $e->getMessage(),
                'log' => (string)$e,
            ]);
        }

        if ($parser->hasErrors()) {
            return $this->renderView('source/_check_error.html.twig', [
                'message' => 'Parser error',
                'log' => $parser->getErrorMessage(),
            ]);
        }

        $statistics = ParserStatistics::calculate($parser);

        return $this->renderView('source/_check_items.html.twig', [
            'items' => $parser->getItems(),
            'totalCount' => $statistics->getTotalCount(),
            'descriptionCount' => $statistics->getDescriptionCount(),
            'startDateCount' => $statistics->getStartDateCount(),
            'endDateCount' => $statistics->getEndDateCount(),
        ]);
    }
}
