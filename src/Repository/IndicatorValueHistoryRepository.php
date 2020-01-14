<?php

namespace App\Repository;

use App\Entity\IndicatorValueHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IndicatorValueHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndicatorValueHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndicatorValueHistory[]    findAll()
 * @method IndicatorValueHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicatorValueHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndicatorValueHistory::class);
    }

}
