<?php

namespace App\Repository;

use App\Entity\Indicator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Indicator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indicator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indicator[]    findAll()
 * @method Indicator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicatorRepository extends ServiceEntityRepository
{
    const ALIAS = 'i';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indicator::class);
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Indicator::class, self::ALIAS)
            ->set(self::ALIAS . '.taux1 ', 0)
            ->set(self::ALIAS . '.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source = 'indicator';
        $table_distante = 'indicator_value';

        $alias_distante = IndicatorValueRepository::ALIAS;

        $sql = ' update ' . $table_source . ' ' . self::ALIAS
            . ' inner join ( '
            . ' select ' . $table_source . '_id, avg(taux1) as taux1, avg(taux2) as taux2, enable '
            . ' from ' . $table_distante . ' where enable=true group by ' . $table_source . '_id ) ' . $alias_distante . ' '
            . ' on ' . self::ALIAS . '.id=' . $alias_distante . '.' . $table_source . '_id '
            . ' set ' . self::ALIAS . '.taux1=' . $alias_distante . '.taux1, '
            . self::ALIAS . '.taux2=' . $alias_distante . '.taux2  '
            . ' where ' . self::ALIAS . '.enable=true; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (DBALException $e) {
            return 'Error' . $e->getMessage();
        }
    }

    public function findAllIndicatorContributif()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ActionRepository::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS
            )
            ->leftjoin(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->leftjoin(ActionRepository::ALIAS . '.category', CategoryRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS)
            ->where(AxeRepository::ALIAS . '.enable=true')
            ->andwhere(PoleRepository::ALIAS . '.enable=true')
            ->andwhere(ThematiqueRepository::ALIAS . '.enable=true')
            ->andwhere(CategoryRepository::ALIAS . '.enable=true')
            ->andwhere(self::ALIAS . '.enable=true')
            ->andwhere(self::ALIAS . '.indicatorType=\'contributif\'')
            ->orderBy(AxeRepository::ALIAS . '.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS . '.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS . '.name', 'ASC')
            ->orderBy(CategoryRepository::ALIAS . '.name', 'ASC')
            ->orderBy(ActionRepository::ALIAS . '.name', 'ASC')
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
