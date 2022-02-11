<?php

namespace App\Repository;

use App\Entity\PackageDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PackageDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackageDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackageDetail[]    findAll()
 * @method PackageDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackageDetail::class);
    }

    // /**
    //  * @return PackageDetail[] Returns an array of PackageDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PackageDetail
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
