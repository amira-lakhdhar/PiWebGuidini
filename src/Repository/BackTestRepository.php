<?php

namespace App\Repository;

use App\Entity\BackTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BackTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackTest[]    findAll()
 * @method BackTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackTest::class);
    }

    // /**
    //  * @return BackTest[] Returns an array of BackTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BackTest
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
