<?php

namespace App\Twig;

use App\Repository\TagRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(ContainerInterface $container, TagRepository $tagRepository, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->tagRepository = $tagRepository;
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('navbar_render', [$this, 'navbarRender'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        );
    }

    /**
     * @param Environment $twig
     * @param int|null $tagId
     * @return string
     * @throws Error
     */
    public function navbarRender(Environment $twig, ?int $tagId)
    {
        $tags = $this->tagRepository->findFavorites();

        $phrase = $this->requestStack->getCurrentRequest()->get('q');
        return $twig->render('parts/navbar.html.twig', [
            'tags' => $tags,
            'tagId' => $tagId,
            'phrase' => $phrase,
            'user' => $this->getUser(),
        ]);
    }

    private function getUser()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return null;
        }

        if (!\is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}