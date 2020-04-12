<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findFavorites(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.favorite = :favorite')
            ->setParameter('favorite', true)
            ->getQuery()
            ->getArrayResult();
    }
}