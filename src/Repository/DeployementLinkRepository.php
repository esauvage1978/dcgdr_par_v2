<?php

namespace App\Repository;

use App\Entity\DeployementLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DeployementLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeployementLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeployementLink[]    findAll()
 * @method DeployementLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeployementLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeployementLink::class);
    }

    // /**
    //  * @return DeployementLink[] Returns an array of DeployementLink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeployementLink
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
