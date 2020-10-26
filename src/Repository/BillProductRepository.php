<?php

namespace App\Repository;

use App\Entity\BillProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BillProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillProduct[]    findAll()
 * @method BillProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillProduct::class);
    }

    // /**
    //  * @return BillProduct[] Returns an array of BillProduct objects
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
    public function findOneBySomeField($value): ?BillProduct
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
