<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[]
     */
    public function findAdmins(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = :role')
            ->setParameter('role', User::ROLE_ADMIN)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Source $source
     * @return User[]
     * @throws \Exception
     */
    public function findSourceSubscribers(Source $source): array
    {
        $currentDate = new \DateTime;

        return $this->createQueryBuilder('u')
            ->join('u.subscriptions', 's')
            ->andWhere('s.source = :source')
            ->andWhere('s.isNotify = true')
            ->andWhere('s.expireAt IS NULL OR s.expireAt > :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->setParameter('source', $source)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
