<?php

namespace App\Repository;

use App\Entity\ActionLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActionLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionLink[]    findAll()
 * @method ActionLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionLink::class);
    }

    // /**
    //  * @return ActionLink[] Returns an array of ActionLink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActionLink
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
