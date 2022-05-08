<?php

namespace App\Repository;

use App\Entity\RateDoctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RateDoctor|null find($id, $lockMode = null, $lockVersion = null)
 * @method RateDoctor|null findOneBy(array $criteria, array $orderBy = null)
 * @method RateDoctor[]    findAll()
 * @method RateDoctor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateDoctorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RateDoctor::class);
    }

    // /**
    //  * @return RateDoctor[] Returns an array of RateDoctor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RateDoctor
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
