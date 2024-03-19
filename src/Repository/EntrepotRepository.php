<?php

namespace App\Repository;

use App\Entity\Entrepot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entrepot>
 *
 * @method Entrepot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entrepot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entrepot[]    findAll()
 * @method Entrepot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entrepot::class);
    }

    //    /**
    //     * @return Entrepot[] Returns an array of Entrepot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Entrepot
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
