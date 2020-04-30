<?php


namespace App\Repository;


use App\Dto\DeployementSearchDto;
use App\Entity\Deployement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class DeployementRepositoryDto extends ServiceEntityRepository
{
    const ALIAS = 'd';

    /**
     * @var DeployementSearchDto
     */
    private $dto;

    /**
     * @var QueryBuilder
     */
    private $builder;

    /**
     * @var array
     */
    private $params;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deployement::class);
    }

    private function initialise_select()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ActionRepository::ALIAS,
                OrganismeRepository::ALIAS,

                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS,

                IndicatorRepository::ALIAS,
                IndicatorValueRepository::ALIAS,

                CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS,
                UserRepository::ALIAS_DEPLOYEMENT_WRITERS
            )
            ->innerJoin(self::ALIAS . '.action', ActionRepository::ALIAS)
            ->innerJoin(self::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->innerJoin(ActionRepository::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.indicatorValues', IndicatorValueRepository::ALIAS)
            ->leftJoin(IndicatorValueRepository::ALIAS . '.indicator', IndicatorRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.writers', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS . '.users', UserRepository::ALIAS_DEPLOYEMENT_WRITERS);
    }

    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(OrganismeRepository::ALIAS . '.name', 'ASC');
    }

    public function findAllForDto(DeployementSearchDto $dto)
    {
        $this->dto = $dto;

        $this->initialise_select();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    private function initialise_where()
    {
        $this->params=[];
        $dto = $this->dto;

        $this->builder
            ->where(AxeRepository::ALIAS . '.enable=' . ($dto->actionSearchDto->isAxeEnable() ? 'true' : 'false'))
            ->andwhere(PoleRepository::ALIAS . '.enable=' . ($dto->actionSearchDto->isPoleEnable() ? 'true' : 'false'))
            ->andwhere(ThematiqueRepository::ALIAS . '.enable=' . ($dto->actionSearchDto->isThematiqueEnable() ? 'true' : 'false'))
            ->andwhere(CategoryRepository::ALIAS . '.enable=' . ($dto->actionSearchDto->isCategoryEnable() ? 'true' : 'false'))
            ->andwhere(AxeRepository::ALIAS . '.archiving=' . ($dto->actionSearchDto->isActionArchiving() ? 'true' : 'false'))
            ->andwhere(IndicatorRepository::ALIAS . '.enable=' . ($dto->isIndicatorEnable() ? 'true' : 'false'))
            ->andwhere(IndicatorValueRepository::ALIAS . '.enable=' . ($dto->isIndicatorValueEnable() ? 'true' : 'false'));

        $this->initialise_where_user_writer();

        $this->initialise_where_jalon();

        $this->initialise_where_date_end_of_deployement();

        $this->initialise_where_state();

        $this->initialise_where_states();

        $this->initialise_where_organisme();

        $this->initialise_where_organismes();

        $this->initialise_where_search();

        $this->initialise_where_has_corbeille();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }
    }

    private function initialise_where_user_writer()
    {
        if (!empty($this->dto->getUserWriter())) {
            $this->builder->andwhere(UserRepository::ALIAS_DEPLOYEMENT_WRITERS . '.id = :useridw');
            $this->addParams('useridw', $this->dto->getUserWriter());
        }
    }

    private function initialise_where_state()
    {
        if (!empty($this->dto->actionSearchDto->getState())) {
            $this->builder->andwhere(ActionRepository::ALIAS . '.state = :state');
            $this->addParams('state', $this->dto->actionSearchDto->getState());
        }
    }
    private function initialise_where_states()
    {
        if (!empty($this->dto->actionSearchDto->getStates())) {
            $this->builder->andwhere(ActionRepository::ALIAS . '.state in (:state)');
            $this->addParams('state', $this->dto->actionSearchDto->getStates());
        }
    }
    private function initialise_where_organisme()
    {
        if (!empty($this->dto->getOrganismeId())) {
            $this->builder->andwhere(OrganismeRepository::ALIAS . '.id = :organismeid');
            $this->addParams('organismeid', $this->dto->getOrganismeId());
        }
    }
    private function initialise_where_organismes()
    {
        if (!empty($this->dto->getOrganismesId())) {
            $this->builder->andwhere(OrganismeRepository::ALIAS . '.id in  (:organismes)');
            $this->addParams('organismes', $this->dto->getOrganismesId());
        }
    }

    private function initialise_where_has_corbeille()
    {
        if ($this->dto->getHasWriters()==DeployementSearchDto::WRITERS_PRESENT) {
            $qBLu = $this->createQueryBuilder('id')
                ->innerJoin( 'id.writers', 'cordw')
                ->innerJoin( 'id.organisme', 'org')
                ->where('org.id = :organismeid')
                ->setParameter('organismeid',$this->dto->getOrganismeId());

            $this->builder->andwhere(self::ALIAS. '.id NOT IN (' . $qBLu->getDQL() . ')');
        }
    }

    private function initialise_where_date_end_of_deployement()
    {
        if ($this->dto->getHasDateEndOfDeployement()===DeployementSearchDto::DATE_STATUS_NULL) {
            $this->builder->andwhere(DeployementRepository::ALIAS . '.endAt is null');
        } else if ($this->dto->getHasDateEndOfDeployement()===DeployementSearchDto::DATE_STATUS_NOT_NULL) {
            $this->builder->andwhere(DeployementRepository::ALIAS . '.endAt is not null');
        }

    }

    private function initialise_where_jalon()
    {
        $dto = $this->dto;
        $builder = $this->builder;

        if ($dto->getJalonNotPresent()) {
            $builder->andWhere(self::ALIAS . '.showAt is null ');
        }

        if (!empty($dto->getJalonFrom()) && empty($dto->getJalonTo())) {
            $builder->andWhere(
                self::ALIAS . '.showAt ' .
                $dto->getJalonOperator() . ' :from');
            $this->addParams('from', $dto->getJalonFrom());
        } elseif (!empty($dto->getJalonFrom()) && !empty($dto->getJalonTo())) {
            $builder->andWhere(
                self::ALIAS . '.showAt BETWEEN  :from AND :to');

            $this->addParams('from', $dto->getJalonFrom());
            $this->addParams('to', $dto->getJalonTo());
        }

    }

    private function initialise_where_search()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (!empty($dto->getSearch())) {
            $builder
                ->andwhere(
                    IndicatorValueRepository::ALIAS . '.content like :search'.
                ' OR ' . IndicatorValueRepository::ALIAS . '.goal like :search'.
                ' OR ' . IndicatorValueRepository::ALIAS . '.value like :search');

            $this->addParams('search', '%' . $dto->getSearch() . '%');
        }

        if (!empty($dto->getSearchDate())) {
            $builder
                ->andWhere(
                    self::ALIAS . '.showAt = :search'.
                ' OR ' . self::ALIAS . '.endAt = :search');
            $this->addParams('search',  $dto->getSearchDate() );
        }
    }


    private function addParams($key, $value)
    {
        $onevalue = [$key => $value];
        if (empty($this->params)) {
            $this->params = $onevalue;
        } else {
            $this->params = array_merge($onevalue, $this->params);
        }
    }

}