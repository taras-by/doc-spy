<?php

namespace App\Repository;

use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SourceRepository  extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Source::class);
    }
    /**
     * @var int|null $results
     * @return array
     * @throws \Exception
     */
    public function findForUpdate($results = null)
    {
        return $this->createQueryBuilder('s')
            ->where('s.scheduleAt <= :now')
            ->setParameter('now', new \DateTime())
            ->orWhere('s.scheduleAt is null')
            ->orderBy('s.scheduleAt')
            ->setMaxResults($results)
            ->getQuery()
            ->getResult();
    }
}
