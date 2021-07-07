<?php

namespace App\Repository;

use App\Entity\LicencePlate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LicencePlate|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicencePlate|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicencePlate[]    findAll()
 * @method LicencePlate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicencePlateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicencePlate::class);
    }

    // /**
    //  * @return LicencePlate[] Returns an array of LicencePlate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LicencePlate
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllByUser($value) {
        return $this->createQueryBuilder('l')
            ->andWhere('l.userIds = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
