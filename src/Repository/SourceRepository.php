<?php

namespace App\Repository;

use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Source::class);
    }

    /**
     * @param int|null $results
     * @param bool $force
     * @return array
     */
    public function findForUpdate(?int $results = null, $force = false)
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.id')
            ->where('s.isEnabled = true')
            ->setMaxResults($results);

        if (!$force) {
            $qb
                ->andWhere('s.scheduleAt <= :now')
                ->setParameter('now', new \DateTime())
                ->orWhere('s.scheduleAt is null')
                ->orderBy('s.scheduleAt');
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
