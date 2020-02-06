<?php

namespace App\Repository;

use App\Dto\DeployementSearchDto;
use App\Entity\Action;
use App\Entity\Deployement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;

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
        $table_distante2 = 'indicator';

        $alias_distante = IndicatorValueRepository::ALIAS;
        $alias_distante2 = IndicatorRepository::ALIAS;

        $sql = ' update '.$table_source.' '.self::ALIAS
            .' inner join ( '
            .' select '.$table_source.'_id, avg('.$alias_distante.'.taux1) as taux1, avg('.$alias_distante.'.taux2) as taux2, '.$alias_distante.'.enable '
            .' from '.$table_distante.'  '.$alias_distante.' inner join '.$table_distante2.' '.$alias_distante2.' on '.$alias_distante2.'.id='.$alias_distante.'.indicator_id '
            .' where '.$alias_distante.'.enable=true AND '.$alias_distante2.'.enable=true group by '.$table_source.'_id ) '.$alias_distante.' '
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

    private function findAllForDto_initialise(DeployementSearchDto $dto): QueryBuilder
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ActionRepository::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS,
                UserRepository::ALIAS_DEPLOYEMENT_WRITERS,
                OrganismeRepository::ALIAS,
                IndicatorRepository::ALIAS,
                IndicatorValueRepository::ALIAS
            )

            ->leftjoin(self::ALIAS.'.action', ActionRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.organisme', OrganismeRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.indicatorValues', IndicatorValueRepository::ALIAS)
            ->leftjoin(IndicatorValueRepository::ALIAS.'.indicator', IndicatorRepository::ALIAS)
            ->leftjoin(ActionRepository::ALIAS.'.category', CategoryRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.writers', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS)
            ->leftjoin(CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS.'.users', UserRepository::ALIAS_DEPLOYEMENT_WRITERS);

        $builder
            ->where(AxeRepository::ALIAS.'.enable='.($dto->actionSearchDto->isAxeEnable() ? 'true' : 'false'))
            ->andwhere(PoleRepository::ALIAS.'.enable='.($dto->actionSearchDto->isPoleEnable() ? 'true' : 'false'))
            ->andwhere(ThematiqueRepository::ALIAS.'.enable='.($dto->actionSearchDto->isThematiqueEnable() ? 'true' : 'false'))
            ->andwhere(CategoryRepository::ALIAS.'.enable='.($dto->actionSearchDto->isCategoryEnable() ? 'true' : 'false'))
            ->andwhere(AxeRepository::ALIAS.'.archiving='.($dto->actionSearchDto->isActionArchiving() ? 'true' : 'false'))
            ->andwhere(CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS.'.enable='.($dto->isCorbeilleEnable() ? 'true' : 'false'))
            ->andwhere(IndicatorRepository::ALIAS.'.enable='.($dto->isIndicatorEnable() ? 'true' : 'false'))
            ->andwhere(IndicatorValueRepository::ALIAS.'.enable='.($dto->isIndicatorValueEnable() ? 'true' : 'false'));

        return $builder;
    }

    private function findAllForDto_orderBy(QueryBuilder $builder): QueryBuilder
    {
        $builder
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(CategoryRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(ActionRepository::ALIAS.'.ref ', 'ASC')
            ->orderBy(ActionRepository::ALIAS.'.name ', 'ASC');

        return $builder;
    }

    public function findAllForDto(DeployementSearchDto $dto)
    {
        $params = [];

        $builder = $this->findAllForDto_initialise($dto);

        if (!empty($dto->getUserWriter())) {
            $builder->andwhere(UserRepository::ALIAS_DEPLOYEMENT_WRITERS.'.id = :userid');

            $params = $this->addParams($params, 'userid', $dto->getUserWriter());
        }

        if ($dto->getJalonNotPresent()) {
            $builder->andWhere(DeployementRepository::ALIAS.'.showAt is null ');
        }

        if (!empty($dto->actionSearchDto->getState())) {
            $builder->andWhere(ActionRepository::ALIAS.'.state = :state ');
            $params = $this->addParams($params, 'state', $dto->actionSearchDto->getState());
        }


        if (!empty($dto->getJalonFrom()) && empty($dto->getJalonTo())) {
            $builder->andWhere(
                DeployementRepository::ALIAS.'.showAt '.
                $dto->getJalonOperator().' :from');

            $params = $this->addParams($params, 'from', $dto->getJalonFrom());
        } elseif (!empty($dto->getJalonFrom()) && !empty($dto->getJalonTo())) {
            $builder->andWhere(
                DeployementRepository::ALIAS.'.showAt BETWEEN  :from AND :to');

            $params = $this->addParams($params, 'from', $dto->getJalonFrom());
            $params = $this->addParams($params, 'to', $dto->getJalonTo());
        }

        if (!empty($dto->getSearch())) {
            $builder
                ->andwhere(IndicatorValueRepository::ALIAS.'.content like :search')
                ->orWhere(IndicatorValueRepository::ALIAS.'.goal like :search')
                ->orWhere(IndicatorValueRepository::ALIAS.'.value like :search')
;

            $params = $this->addParams($params, 'search', '%'.$dto->getSearch().'%');
        }

        if (count($params) > 0) {
            $builder->setParameters($params);
        }

        $builder = $this->findAllForDto_orderBy($builder);
        return $builder
            ->getQuery()
            ->getResult();
    }

    private function addParams($params, $key, $value): array
    {
        $onevalue = [$key => $value];
        if (0 == count($params)) {
            return $onevalue;
        } else {
            $total = array_merge($onevalue, $params);

            return $total;
        }
    }
}
