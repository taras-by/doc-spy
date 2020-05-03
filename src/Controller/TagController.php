<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\ItemRepository;
use App\Repository\TagRepository;
use App\Security\TagVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag/{id}/{page}", name="tag_items", methods={"GET"}, requirements={"id"="\d+", "page"="\d+"})
     * @param ItemRepository $itemRepository
     * @param Tag $tag
     * @param int $page
     * @return Response
     */
    public function items(ItemRepository $itemRepository, Tag $tag, $page = 1)
    {
        $this->denyAccessUnlessGranted(TagVoter::VIEW, $tag);

        $items = $itemRepository->findPaginatedByTagId($tag->getId(), $page, Item::LIMIT, $this->getUser());

        $maxPages = ceil($items->count() / Item::LIMIT);

        return $this->render('tag/items.html.twig', [
            'items' => $items,
            'tag' => $tag,
            'maxPages' => $maxPages,
            'page' => $page,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/tags", name="tag_list", methods={"GET"})
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function list(TagRepository $tagRepository): Response
    {
        return $this->render('tag/list.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/tag/new", name="tag_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            $this->addFlash('success', sprintf('Tag added'));
            return $this->redirectToRoute('tag_show', ['id' => $tag->getId()]);
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/tag/{id}/show", name="tag_show", methods={"GET"})
     * @param Tag $tag
     * @return Response
     */
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/tag/{id}/edit", name="tag_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Tag $tag
     * @return Response
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf('Tag saved'));
            return $this->redirectToRoute('tag_show', ['id' => $tag->getId()]);
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/tag/{id}", name="tag_delete", methods={"DELETE"})
     * @param Request $request
     * @param Tag $tag
     * @return Response
     */
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();

            $this->addFlash('success', sprintf('Tag "%s" deleted', $tag->getName()));
        }

        return $this->redirectToRoute('tag_list');
    }
}
