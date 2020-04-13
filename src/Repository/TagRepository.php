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
            ->where('t.favorite = :favorite')
            ->setParameter('favorite', true)
            ->getQuery()
            ->getArrayResult();
    }
}