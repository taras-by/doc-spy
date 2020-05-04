<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findFavorites(): array
    {
        return $this->getOrderedQueryBuilder()
            ->where('t.isFavorite = true')
            ->andWhere('t.isEnabled = true')
            ->getQuery()
            ->getArrayResult();
    }

    public function getOrderedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.order')
            ->addOrderBy('t.name');
    }
}
