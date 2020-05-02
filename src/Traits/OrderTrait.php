<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait OrderTrait
{
    /**
     * @var integer
     *
     * @Assert\PositiveOrZero
     * @ORM\Column(name="tag_order", type="integer", options={"default":0})
     */
    private $order = 0;

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return self
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }
}
