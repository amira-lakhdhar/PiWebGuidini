<?php

namespace App\Repository;

use App\Entity\Compagnieaerienne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Compagnieaerienne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compagnieaerienne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compagnieaerienne[]    findAll()
 * @method Compagnieaerienne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompagnieaerienneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compagnieaerienne::class);
    }

    // /**
    //  * @return Compagnieaerienne[] Returns an array of Compagnieaerienne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Compagnieaerienne
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
