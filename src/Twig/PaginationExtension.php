<?php

namespace App\Twig;

class PaginationExtension extends \Twig_Extension
{
    const MORE_PAGES = 2;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('pagination_render', [$this, 'render'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ])
        );
    }

    /**
     * @param \Twig_Environment $environment
     * @param int $maxPages
     * @param int $thisPage
     * @param string $route
     * @param array $query
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(\Twig_Environment $environment, int $maxPages, int $thisPage, string $route, array $query = [])
    {
        if ($thisPage > self::MORE_PAGES) {
            $start = $thisPage - self::MORE_PAGES;
        } else {
            $start = 1;
        }

        if ($thisPage < $maxPages - self::MORE_PAGES) {
            $end = $thisPage + self::MORE_PAGES;
        } else {
            $end = $maxPages;
        }

        if ($thisPage > self::MORE_PAGES + 1) {
            $view_first = true;
        } else {
            $view_first = false;
        }

        if ($thisPage < $maxPages - self::MORE_PAGES) {
            $view_last = true;
        } else {
            $view_last = false;
        }

        if ($thisPage > self::MORE_PAGES + 2) {
            $view_left_dots = true;
        } else {
            $view_left_dots = false;
        }

        if ($thisPage < $maxPages - self::MORE_PAGES - 1) {
            $view_right_dots = true;
        } else {
            $view_right_dots = false;
        }

        return $environment->render('parts/pagnation.html.twig', [
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
            'start' => $start,
            'end' => $end,
            'view_first' => $view_first,
            'view_last' => $view_last,
            'view_left_dots' => $view_left_dots,
            'view_right_dots' => $view_right_dots,
            'route' => $route,
            'query' => $query,
        ]);
    }
}