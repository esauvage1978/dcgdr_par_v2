<?php

namespace App\Repository;

use App\Entity\DeployementFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DeployementFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeployementFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeployementFile[]    findAll()
 * @method DeployementFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeployementFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeployementFile::class);
    }

    // /**
    //  * @return DeployementFile[] Returns an array of DeployementFile objects
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
    public function findOneBySomeField($value): ?DeployementFile
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
