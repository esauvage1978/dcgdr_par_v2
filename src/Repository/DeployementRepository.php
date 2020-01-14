<?php

namespace App\Repository;

use App\Entity\Action;
use App\Entity\Deployement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Deployement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deployement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deployement[]    findAll()
 * @method Deployement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeployementRepository extends ServiceEntityRepository
{
    const ALIAS = 'd';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deployement::class);
    }

    public function findAllForAction(string $actionId)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS,
                ActionRepository::ALIAS)
            ->join(self::ALIAS.'.action', ActionRepository::ALIAS)
            ->join(self::ALIAS.'.organisme', OrganismeRepository::ALIAS)
            ->andWhere(ActionRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $actionId);

        $builder = $builder
            ->orderBy(OrganismeRepository::ALIAS.'.name', 'ASC');

        return $builder
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
        $table_source = 'deployement';
        $table_distante = 'indicator_value';

        $alias_distante = IndicatorValueRepository::ALIAS;

        $sql = ' update '.$table_source.' '.self::ALIAS
            .' inner join ( '
            .' select '.$table_source.'_id, avg(taux1) as taux1, avg(taux2) as taux2, enable '
            .' from '.$table_distante.' where enable=true group by '.$table_source.'_id ) '.$alias_distante.' '
            .' on '.self::ALIAS.'.id='.$alias_distante.'.'.$table_source.'_id '
            .' set '.self::ALIAS.'.taux1='.$alias_distante.'.taux1, '
            .self::ALIAS.'.taux2='.$alias_distante.'.taux2 ; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (DBALException $e) {
            return 'Error'.$e->getMessage();
        }
    }


}
