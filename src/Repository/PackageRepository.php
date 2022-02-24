<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Package|null find($id, $lockMode = null, $lockVersion = null)
 * @method Package|null findOneBy(array $criteria, array $orderBy = null)
 * @method Package[]    findAll()
 * @method Package[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    public function findAllByBuilding($id)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.building = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();
    }

    public function findAllhandOver($idBuilding)
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.resident', 're')
            ->andWhere('r.building = :id')
            ->andWhere('r.isHandedOver = :over OR r.isHandedOver is null')
            ->andWhere('re.isEnabled = :enabled')
            ->setParameters([
                'id' => $idBuilding,
                'over' => false,
                'enabled' => true,
            ]);

        return $qb->getQuery()->getResult();
    }

    public function countPackageNoHandedOver($idBuilding)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('SUM(r.nbPackage) AS nb')
            ->innerJoin('r.resident', 're')
            ->andWhere('r.building = :id')
            ->andWhere('r.isHandedOver = :over OR r.isHandedOver is null')
            ->andWhere('re.isEnabled = :enabled')
            ->setParameters([
                'id' => $idBuilding,
                'over' => false,
                'enabled' => true,
            ]);

        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Package[] Returns an array of Package objects
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
    public function findOneBySomeField($value): ?Package
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
