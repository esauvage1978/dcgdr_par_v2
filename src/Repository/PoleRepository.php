<?php

namespace App\Repository;

use App\Entity\Pole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Pole|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pole|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pole[]    findAll()
 * @method Pole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoleRepository extends ServiceEntityRepository
{
    const ALIAS='p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pole::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS, AxeRepository::ALIAS, ThematiqueRepository::ALIAS, CategoryRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.thematiques', ThematiqueRepository::ALIAS)
            ->leftJoin(ThematiqueRepository::ALIAS.'.categories', CategoryRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllFillComboboxForAxe(string $axeId, string $enable = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, ' .self::ALIAS . '.name')
            ->join(self::ALIAS.'.axe', AxeRepository::ALIAS)
            ->andWhere(AxeRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $axeId);

        if ('all' != $enable) {
            $builder = $builder
                ->andWhere(AxeRepository::ALIAS.'.enable = :val2')
                ->andWhere(self::ALIAS.'.enable = :val2')
                ->setParameter('val2', $enable);
        }

        $builder = $builder
            ->orderBy(self::ALIAS.'.name', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }


    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Pole::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source='pole';
        $table_distante='thematique';

        $alias_distante=ThematiqueRepository::ALIAS;

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
