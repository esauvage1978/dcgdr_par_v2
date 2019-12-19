<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    const ALIAS = 'ca';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                ActionRepository::ALIAS
            )
            ->innerJoin(self::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->innerJoin(self::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllFillComboboxForThematique(string $thematiqueId, string $enable = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, '.self::ALIAS.'.name, '.self::ALIAS.'.ref')
            ->join(self::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->andWhere(ThematiqueRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $thematiqueId);

        if ('all' != $enable) {
            $builder = $builder
                ->andWhere(self::ALIAS.'.enable = :val2')
                ->andWhere(ThematiqueRepository::ALIAS.'.enable = :val2')
                ->setParameter('val2', $enable);
        }

        $builder = $builder
            ->orderBy(self::ALIAS.'.ref', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Category::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source = 'category';
        $table_distante = 'action';

        $alias_distante = ActionRepository::ALIAS;

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
