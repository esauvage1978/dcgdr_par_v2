<?php

namespace App\Repository;

use App\Entity\Pole;
use App\Entity\Thematique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Thematique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thematique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thematique[]    findAll()
 * @method Thematique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematiqueRepository extends ServiceEntityRepository
{
    const ALIAS = 't';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thematique::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                CategoryRepository::ALIAS,
                ActionRepository::ALIAS
            )
            ->leftJoin(self::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.categories', CategoryRepository::ALIAS)
            ->leftJoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftJoin(CategoryRepository::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllFillComboboxForPole(string $poleId, string $enable = 'all')
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.id, '.self::ALIAS.'.name, '.self::ALIAS .'.ref')
            ->join(self::ALIAS.'.pole', PoleRepository::ALIAS)
            ->andWhere(PoleRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $poleId);

        if ('all' != $enable) {
            $builder = $builder
                ->andWhere(self::ALIAS.'.enable = :val2')
                ->andWhere(PoleRepository::ALIAS.'.enable = :val2')
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
        $queryBuilder->update(Thematique::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source='thematique';
        $table_distante='category';

        $alias_distante=CategoryRepository::ALIAS;

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
