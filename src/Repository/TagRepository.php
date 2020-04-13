<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }
    public function findFavorites(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.favorite = :favorite')
            ->setParameter('favorite', true)
            ->getQuery()
            ->getArrayResult();
    }
}