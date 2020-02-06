<?php

namespace App\Repository;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    const ALIAS = 'ac';

    const FILTRE_DTO_INIT_TABLEAU='tableau';
    const FILTRE_DTO_INIT_SEARCH='search';
    const FILTRE_DTO_INIT_UNITAIRE='unitaire';
    const FILTRE_DTO_INIT_AJAX='ajax';


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Action::class);
    }

    public function findAllEnableAndNotArchiving()
    {
        $builder = $this->builderFindAllAction();

        $builder
            ->Where(AxeRepository::ALIAS.'.archiving=false');

        return $builder
            ->getQuery()
            ->getResult();
    }

    private function builderFindAllAction(): QueryBuilder
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS
                )
            ->leftjoin(self::ALIAS.'.category', CategoryRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->where(AxeRepository::ALIAS.'.enable=true')
            ->andwhere(PoleRepository::ALIAS.'.enable=true')
            ->andwhere(ThematiqueRepository::ALIAS.'.enable=true')
            ->andwhere(CategoryRepository::ALIAS.'.enable=true')
            ->andwhere(self::ALIAS.'.enable=true');

        return $builder
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS.'.name', 'ASC')
            ->orderBy(CategoryRepository::ALIAS.'.name', 'ASC')
            ->orderBy(self::ALIAS.'.name', 'ASC');
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

    private function findForDto_initialise_search(ActionSearchDto $dto): QueryBuilder
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                IndicatorRepository::ALIAS
            )

            ->leftjoin(self::ALIAS.'.category', CategoryRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.indicators', IndicatorRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
;


        return $builder;
    }
    private function findForDto_initialise_unitaire(ActionSearchDto $dto): QueryBuilder
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                CorbeilleRepository::ALIAS_ACTION_WRITERS,
                UserRepository::ALIAS_ACTION_WRITERS,
                CorbeilleRepository::ALIAS_ACTION_VALIDERS,
                UserRepository::ALIAS_ACTION_VALIDERS,
                CorbeilleRepository::ALIAS_ACTION_READERS,
                DeployementRepository::ALIAS,
                CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS,
                OrganismeRepository::ALIAS,
                IndicatorRepository::ALIAS,
                IndicatorValueRepository::ALIAS
            )

            ->leftjoin(self::ALIAS.'.category', CategoryRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.deployements', DeployementRepository::ALIAS)
            ->leftjoin(DeployementRepository::ALIAS.'.organisme', OrganismeRepository::ALIAS)
            ->leftjoin(DeployementRepository::ALIAS.'.writers', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS)
            ->leftjoin(DeployementRepository::ALIAS.'.indicatorValues', IndicatorValueRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.indicators', IndicatorRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS)
            ->leftjoin(CorbeilleRepository::ALIAS_ACTION_WRITERS.'.users', UserRepository::ALIAS_ACTION_WRITERS)
            ->leftjoin(self::ALIAS.'.validers', CorbeilleRepository::ALIAS_ACTION_VALIDERS)
            ->leftjoin(CorbeilleRepository::ALIAS_ACTION_VALIDERS.'.users', UserRepository::ALIAS_ACTION_VALIDERS)
            ->leftjoin(self::ALIAS.'.readers', CorbeilleRepository::ALIAS_ACTION_READERS);



        return $builder;
    }
    private function findForDto_initialise_rqt_ajax(ActionSearchDto $dto): QueryBuilder
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,
                CorbeilleRepository::ALIAS_ACTION_WRITERS,
                UserRepository::ALIAS_ACTION_WRITERS,
                CorbeilleRepository::ALIAS_ACTION_VALIDERS,
                UserRepository::ALIAS_ACTION_VALIDERS,
                CorbeilleRepository::ALIAS_ACTION_READERS
            )
            ->leftjoin(self::ALIAS.'.category', CategoryRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS)
            ->leftjoin(CorbeilleRepository::ALIAS_ACTION_WRITERS.'.users', UserRepository::ALIAS_ACTION_WRITERS)
            ->leftjoin(self::ALIAS.'.validers', CorbeilleRepository::ALIAS_ACTION_VALIDERS)
            ->leftjoin(CorbeilleRepository::ALIAS_ACTION_VALIDERS.'.users', UserRepository::ALIAS_ACTION_VALIDERS)
            ->leftjoin(self::ALIAS.'.readers', CorbeilleRepository::ALIAS_ACTION_READERS)
            ;

        return $builder;
    }
    private function findForDto_initialise_tableau(ActionSearchDto $dto): QueryBuilder
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS
            )
            ->leftjoin(self::ALIAS.'.category', CategoryRepository::ALIAS)
            ->leftjoin(CategoryRepository::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->leftjoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftjoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ;

        return $builder;
    }


    private function findAllForDto_orderBy(QueryBuilder $builder): QueryBuilder
    {
        $builder
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(CategoryRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(self::ALIAS.'.ref ', 'ASC')
            ->orderBy(self::ALIAS.'.name ', 'ASC');

        return $builder;
    }

    public function findAllForDto(ActionSearchDto $dto, string $filtre)
    {
        $params = [];

        switch ($filtre)
        {
            case self::FILTRE_DTO_INIT_TABLEAU:
                $builder = $this->findForDto_initialise_tableau($dto);
                break;
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $builder = $this->findForDto_initialise_unitaire($dto);
                break;
            case self::FILTRE_DTO_INIT_AJAX:
                $builder = $this->findForDto_initialise_rqt_ajax($dto);
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $builder = $this->findForDto_initialise_search($dto);
                break;
        }
        if(empty($dto->getId())) {
            $builder
                ->where(AxeRepository::ALIAS . '.enable=' . ($dto->isAxeEnable() ? 'true' : 'false'))
                ->andwhere(PoleRepository::ALIAS . '.enable=' . ($dto->isPoleEnable() ? 'true' : 'false'))
                ->andwhere(ThematiqueRepository::ALIAS . '.enable=' . ($dto->isThematiqueEnable() ? 'true' : 'false'))
                ->andwhere(CategoryRepository::ALIAS . '.enable=' . ($dto->isCategoryEnable() ? 'true' : 'false'))
                ->andwhere(AxeRepository::ALIAS . '.archiving=' . ($dto->isActionArchiving() ? 'true' : 'false'));
        } else {
            $builder->andwhere(self::ALIAS.'.id = :id');

            $params = $this->addParams($params, 'id', $dto->getId());
        }

        if (!empty($dto->getUserWriter())) {
            $builder->andwhere(UserRepository::ALIAS_ACTION_WRITERS.'.id = :userid');
            $params = $this->addParams($params, 'userid', $dto->getUserWriter());
        }

        if (!empty($dto->getUserValider())) {
            $builder->andwhere(UserRepository::ALIAS_ACTION_VALIDERS.'.id = :userid');
            $params = $this->addParams($params, 'userid', $dto->getUserValider());
        }

        if ($dto->getJalonNotPresent()) {
            $builder->andWhere(self::ALIAS.'.showAt is null ');
        }

        if (!empty($dto->getJalonFrom()) && empty($dto->getJalonTo())) {
            $builder->andWhere(
                self::ALIAS.'.showAt '.
                $dto->getJalonOperator().' :from');

            $params = $this->addParams($params, 'from', $dto->getJalonFrom());
        } elseif (!empty($dto->getJalonFrom()) && !empty($dto->getJalonTo())) {
            $builder->andWhere(
                self::ALIAS.'.showAt BETWEEN  :from AND :to');

            $params = $this->addParams($params, 'from', $dto->getJalonFrom());
            $params = $this->addParams($params, 'to', $dto->getJalonTo());
        }

        if (!empty($dto->getState())) {
            $builder->andwhere(self::ALIAS.'.state = :state');

            $params = $this->addParams($params, 'state', $dto->getState());
        }

        if (!empty($dto->getAxeId())) {
            $builder->andwhere(AxeRepository::ALIAS.'.id = :axeid');

            $params = $this->addParams($params, 'axeid', $dto->getAxeId());
        }

        if (!empty($dto->getActionRef())) {
            if ('*' != $dto->getActionRef()) {
                $builder->andwhere(self::ALIAS.'.ref = :actionref');
                $params = $this->addParams($params, 'actionref', $dto->getActionRef());
            }
            if ('*' != $dto->getCategoryRef()) {
                $builder->andwhere(CategoryRepository::ALIAS.'.ref = :categoryref');
                $params = $this->addParams($params, 'categoryref', $dto->getCategoryRef());
            }
            if ('*' != $dto->getThematiqueRef()) {
                $builder->andwhere(ThematiqueRepository::ALIAS.'.ref = :thematiqueref');
                $params = $this->addParams($params, 'thematiqueref', $dto->getThematiqueRef());
            }
        } elseif (!empty($dto->getSearch())) {
            $builder
                ->andwhere(self::ALIAS.'.name like :search')
                ->orWhere(self::ALIAS.'.content like :search')
                ->orWhere(self::ALIAS.'.cadrage like :search')
                ->orWhere(IndicatorRepository::ALIAS.'.name like :search')
                ->orWhere(IndicatorRepository::ALIAS.'.content like :search')
                ->orWhere(CategoryRepository::ALIAS.'.name like :search')
                ->orWhere(ThematiqueRepository::ALIAS.'.name like :search')
                ->orWhere(PoleRepository::ALIAS.'.name like :search')
                ->orWhere(AxeRepository::ALIAS.'.name like :search');

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
            .' where '.self::ALIAS.'.enable=true; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (DBALException $e) {
            return 'Error'.$e->getMessage();
        }
    }
}
