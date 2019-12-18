<?php

namespace App\Repository;

use App\Entity\Axe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Axe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Axe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Axe[]    findAll()
 * @method Axe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AxeRepository extends ServiceEntityRepository
{
    const ALIAS = 'a';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Axe::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS, PoleRepository::ALIAS, ThematiqueRepository::ALIAS, CategoryRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.poles', PoleRepository::ALIAS)
            ->leftJoin(PoleRepository::ALIAS.'.thematiques', ThematiqueRepository::ALIAS)
            ->leftJoin(ThematiqueRepository::ALIAS.'.categories', CategoryRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllFillCombobox(string $enable = 'all', string $archiving = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, '.self::ALIAS.'.name');

        if ('all' != $enable) {
            $builder = $builder
                ->Where(self::ALIAS.'.enable = :val1')
                ->setParameter('val1', $enable);
        }

        if ('all' != $archiving) {
            $builder = $builder
                ->andWhere(self::ALIAS.'.archiving = :val2')
                ->setParameter('val2', $archiving);
        }

        $builder = $builder->orderBy(self::ALIAS.'.name', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }


    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Axe::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source='axe';
        $table_distante='pole';

        $alias_distante=PoleRepository::ALIAS;

        $sql = ' update '.$table_source.' '.self::ALIAS
            .' inner join ( '
            .' select '.$table_source.'_id, avg(taux1) as taux1, avg(taux2) as taux2, enable '
            .' from '.$table_distante.' where enable=true group by '.$table_source.'_id ) '.$alias_distante.' '
            .' on '.self::ALIAS.'.id='.$alias_distante.'.'.$table_source.'_id '
            .' set '.self::ALIAS.'.taux1='.$alias_distante.'.taux1, '
            .self::ALIAS.'.taux2='.$alias_distante.'.taux2 '
            .' where '.self::ALIAS.'.enable=true; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (DBALException $e) {
            return 'Error'.$e->getMessage();
        }
    }
}
