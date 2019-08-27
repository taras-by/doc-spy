<?php

namespace App\Twig;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class NavigationExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('navbar_render', [$this, 'navbarRender'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param int|null $tagId
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function navbarRender(\Twig_Environment $twig, ?int $tagId)
    {
        /** @var TagRepository $tagsRepository */
        $tagsRepository = $this->entityManager->getRepository(Tag::class);
        $tags = $tagsRepository->findFavorites();

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
            return;
        }

        if (!\is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
}