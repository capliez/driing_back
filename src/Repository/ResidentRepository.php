<?php

namespace App\Repository;

use App\Entity\Resident;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Resident|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resident|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resident[]    findAll()
 * @method Resident[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResidentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resident::class);
    }

    public function findAllByBuilding($id)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.building = :id')
            ->andWhere('r.isEnabled = :enabled')
            ->setParameter('id', $id)
            ->setParameter('enabled', true);

        return $qb->getQuery()->getResult();
    }


    public function searchResidentByBuilding($idBuilding, $lastName, $isHandedOver)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.building = :idBuilding')
            ->andWhere('r.isEnabled = :isEnabled')
            ->setParameter('idBuilding', $idBuilding)
            ->setParameter('isEnabled', true);


        $searchTerms = $this->extractSearchTerms($lastName);

        if(\count($searchTerms) > 0 && $lastName){

            foreach ($searchTerms as $key => $term) {
                $qb
                    ->andWhere('r.lastName LIKE :t_'.$key)
                    ->setParameter('t_'.$key, '%'.$term.'%')
                ;
            }
        }

        if($isHandedOver) {
            $qb
                ->innerJoin('r.packages', 'p')
                ->andWhere('p.isHandedOver = :isHandedOver')
                ->setParameter('isHandedOver', $isHandedOver)
            ;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Transforms the search string into an array of search terms.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = trim(preg_replace('/[[:space:]]+/', ' ', $searchQuery));
        $terms = array_unique(explode(' ', $searchQuery));

        // ignore the search terms that are too short
        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }


    // /**
    //  * @return Resident[] Returns an array of Resident objects
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
    public function findOneBySomeField($value): ?Resident
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
