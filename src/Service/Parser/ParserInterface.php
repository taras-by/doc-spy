<?php

namespace App\Service\Parser;

use App\Entity\Source;
use Doctrine\Common\Collections\ArrayCollection;

interface ParserInterface
{
    public function getAllCount(): int;

    public function getNeedAddCount(): int;

    public function hasErrors(): bool;

    public function getItems(Source $source): ArrayCollection;
}