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
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
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
        $tags = $this->tagRepository->findFavorites();

        return $twig->render('parts/tags.html.twig', [
            'tags' => $tags,
            'currentTagId' => $currentTagId,
        ]);
    }
}
