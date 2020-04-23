<?php

namespace App\Helper;

use App\Entity\Item;
use App\Parser\ParserInterface;

class ParserStatistics
{
    /**
     * @var int
     */
    private $totalCount = 0;

    /**
     * @var int
     */
    private $descriptionCount = 0;

    /**
     * @var int
     */
    private $startDateCount = 0;

    /**
     * @var int
     */
    private $endDateCount = 0;

    /**
     * @param ParserInterface $parser
     * @return ParserStatistics
     */
    public static function calculate(ParserInterface $parser): self
    {
        $statistics = new self();

        /** @var Item $item */
        foreach ($parser->getItems() as $item) {
            if ($item->getDescription()) {
                $statistics->descriptionCount++;
            }
            if ($item->getStartDate()) {
                $statistics->startDateCount++;
            }
            if ($item->getEndDate()) {
                $statistics->endDateCount++;
            }
        }

        $statistics->totalCount = $parser->getCount();

        return $statistics;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getDescriptionCount(): int
    {
        return $this->descriptionCount;
    }

    /**
     * @return int
     */
    public function getStartDateCount(): int
    {
        return $this->startDateCount;
    }

    /**
     * @return int
     */
    public function getEndDateCount(): int
    {
        return $this->endDateCount;
    }

}