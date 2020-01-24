<?php

namespace App\Repository;

use App\Entity\CadrageFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CadrageFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CadrageFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CadrageFile[]    findAll()
 * @method CadrageFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CadrageFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CadrageFile::class);
    }

    // /**
    //  * @return CadrageFile[] Returns an array of CadrageFile objects
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
    public function findOneBySomeField($value): ?CadrageFile
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
