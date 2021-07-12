<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    /**
     * @return Activity|null Returns an Activity object
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByBlocker($value): ?Activity
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.blocker = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @return Activity|null Returns an Activity object
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByBlockee($value): ?Activity
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.blockee = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
