<?php

namespace App\Repository;

use App\Entity\CadrageLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CadrageLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method CadrageLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method CadrageLink[]    findAll()
 * @method CadrageLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CadrageLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CadrageLink::class);
    }

    // /**
    //  * @return CadrageLink[] Returns an array of CadrageLink objects
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
    public function findOneBySomeField($value): ?CadrageLink
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
