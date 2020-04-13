<?php

namespace App\Twig;

use App\Repository\TagRepository;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TagsExtension extends AbstractExtension
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
            new TwigFunction('tags_render', [$this, 'tagsRender'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        );
    }

    /**
     * @param Environment $twig
     * @param int $currentTagId
     * @return string
     * @throws Error
     */
    public function tagsRender(Environment $twig, int $currentTagId = null)
    {
        $tags = $this->tagRepository->findFavorites();

        return $twig->render('parts/tags.html.twig', [
            'tags' => $tags,
            'currentTagId' => $currentTagId,
        ]);
    }
}
