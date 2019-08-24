<?php

namespace App\Twig;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TagsExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RegistryInterface
     */
    private $entityManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('tags_render', [$this, 'tagsRender'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function tagsRender(\Twig_Environment $twig, int $currentTagId = null)
    {
        /** @var TagRepository $tagsRepository */
        $tagsRepository = $this->entityManager->getRepository(Tag::class);
        $tags = $tagsRepository->findFavorites();

        return $twig->render('parts/tags.html.twig', [
            'tags' => $tags,
            'currentTagId' => $currentTagId,
        ]);
    }
}
