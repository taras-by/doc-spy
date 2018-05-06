<?php

namespace App\Repository;

use App\Entity\Item;

/**
 * ItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return array
     */
    public function findLast()
    {
        return $this->createQueryBuilder('i')
            //->select(['i.title', 'i.link'])
            ->leftJoin('i.source', 's')
            ->addSelect('s')
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(60)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param integer $id
     * @return array
     */
    public function findByTagId($id)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.source', 's')
            ->leftJoin('s.tags', 't')
            ->addSelect('s')
            ->where('t.id = :tag_id')
            ->setParameter('tag_id', $id)
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(60)
            ->getQuery()
            ->getArrayResult();
    }

    public function findBySourceId($id)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.source', 's')
            ->addSelect('s')
            ->where('s.id = :source_id')
            ->setParameter('source_id', $id)
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getArrayResult();
    }

    public function findByPhrase($phrase)
    {
        $query = $this->createQueryBuilder('i')
            ->leftJoin('i.source', 's')
            ->addSelect('s');

        foreach (explode(' ', $phrase) as $i => $word) {
            $query->andWhere('i.title like :word_' . $i)
                ->setParameter('word_' . $i, '%' . $word . '%');
        }

        return $query->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return array
     */
    public function findFromFavoriteSources()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.source', 's')
            ->addSelect('s')
            ->where('s.favorite = :favorite')
            ->setParameter('favorite', true)
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(60)
            ->getQuery()
            ->getArrayResult();
    }
}