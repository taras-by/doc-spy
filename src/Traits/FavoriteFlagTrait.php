<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait FavoriteFlagTrait
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_favorite", type="boolean")
     */
    private $isFavorite = false;

    /**
     *
     * @param boolean $isFavorite
     * @return self
     */
    public function setFavorite($isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isFavorite(): bool
    {
        return $this->isFavorite;
    }

    public static function getFavoriteLabels()
    {
        return [
            false => 'No',
            true => 'Yes',
        ];
    }

    public static function getFavoriteLabelClasses()
    {
        return [
            false => 'light',
            true => 'warning',
        ];
    }

    public function getFavoriteLabel(): string
    {
        return self::getFavoriteLabels()[$this->isFavorite];
    }

    public function getFavoriteLabelClass(): string
    {
        return self::getFavoriteLabelClasses()[$this->isFavorite];
    }

    public static function getFavoriteChoices(): array
    {
        return array_flip(self::getFavoriteLabels());
    }
}
