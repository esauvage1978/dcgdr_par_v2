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
    const ALIAS = 'ivh';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndicatorValueHistory::class);
    }

    public function getLastEntry(string $indicatorValueId)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS
            )
            ->leftjoin(self::ALIAS.'.indicatorValue', IndicatorValueRepository::ALIAS)
            ->where(IndicatorValueRepository::ALIAS.'.id = :id')
            ->setParameter('id', $indicatorValueId)
            ->orderBy(self::ALIAS.'.id', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
