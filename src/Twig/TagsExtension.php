<?php

namespace App\Twig;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Traits\EntityManagerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TagsExtension extends \Twig_Extension
{
    use EntityManagerTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

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
     * @param int $currentTagId
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
