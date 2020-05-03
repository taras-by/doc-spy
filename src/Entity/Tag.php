<?php

namespace App\Entity;

use App\Traits\EnableFlagTrait;
use App\Traits\FavoriteFlagTrait;
use App\Traits\OrderTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    use EnableFlagTrait;
    use FavoriteFlagTrait;
    use OrderTrait;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Many Tags have Many Sources
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Source", mappedBy="tags")
     */
    private $sources;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sources = new ArrayCollection();
        $this->order = 99;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add source
     *
     * @param Source $source
     *
     * @return Tag
     */
    public function addSource(Source $source)
    {
        $this->sources[] = $source;

        return $this;
    }

    /**
     * Remove source
     *
     * @param Source $source
     */
    public function removeSource(Source $source)
    {
        $this->sources->removeElement($source);
    }

    /**
     * Get sources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSources()
    {
        return $this->sources;
    }
}
