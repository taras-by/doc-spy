<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }
    public function findFavorites(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isFavorite = true')
            ->andWhere('t.isEnabled = true')
            ->orderBy('t.order')
            ->addOrderBy('t.name')
            ->getQuery()
            ->getArrayResult();
    }
}