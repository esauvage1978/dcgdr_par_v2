<?php

namespace App\Repository;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ActionRepositoryDto
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);
    }

    public function findAllActionsforCategoryForViewSmallCard(string $categoryId)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS.'.ref, '.self::ALIAS.'.name')
            ->where(self::ALIAS.'.category = :cat')
            ->setParameter('cat', $categoryId)
            ->orderBy(self::ALIAS.'.ref', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->update(Action::class, self::ALIAS)
            ->set(self::ALIAS.'.taux1 ', 0)
            ->set(self::ALIAS.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source = 'action';
        $table_distante = 'indicator';

        $alias_distante = IndicatorRepository::ALIAS;

        $sql = ' update '.$table_source.' '.self::ALIAS
            .' inner join ( '
            .' select '.$table_source.'_id, avg(taux1) as taux1, avg(taux2) as taux2, enable '
            .' from '.$table_distante.' where enable=true group by '.$table_source.'_id ) '.$alias_distante.' '
            .' on '.self::ALIAS.'.id='.$alias_distante.'.'.$table_source.'_id '
            .' set '.self::ALIAS.'.taux1='.$alias_distante.'.taux1, '
            .self::ALIAS.'.taux2='.$alias_distante.'.taux2 '
            .' where '.self::ALIAS.'.state in ( \'started\',\'cotech\',\'codir\',\'finalised\',\'deployed\',\'measured\',\'clotured\')';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (DBALException $e) {
            return 'Error'.$e->getMessage();
        }
    }
}
