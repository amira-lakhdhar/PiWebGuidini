<?php

namespace App\Repository;

use App\Entity\FrontTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FrontTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FrontTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FrontTest[]    findAll()
 * @method FrontTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrontTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FrontTest::class);
    }

    // /**
    //  * @return FrontTest[] Returns an array of FrontTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FrontTest
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
