<?php

namespace App\Repository;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ActionRepositoryDto extends ServiceEntityRepository
{
    const ALIAS = 'ac';

    const FILTRE_DTO_INIT_TABLEAU = 'tableau';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';
    const FILTRE_DTO_INIT_AJAX = 'ajax';

    /**
     * @var ActionSearchDto
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
        $params = [];
        parent::__construct($registry, Action::class);
    }

    private function initialise_select()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                ThematiqueRepository::ALIAS,
                PoleRepository::ALIAS,
                AxeRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->innerJoin(CategoryRepository::ALIAS . '.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS . '.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS . '.axe', AxeRepository::ALIAS);
    }

    private function initialise_select_for_search()
    {
        $this->initialise_select();
        $this->builder
            ->addSelect(IndicatorRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.indicators', IndicatorRepository::ALIAS);
    }

    private function initialise_select_for_unitaire()
    {
        $this->initialise_select();
        $this->builder
            ->addSelect(
                DeployementRepository::ALIAS,
                OrganismeRepository::ALIAS,
                CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS,

                IndicatorRepository::ALIAS,

                CorbeilleRepository::ALIAS_ACTION_WRITERS,
                UserRepository::ALIAS_ACTION_WRITERS,
                CorbeilleRepository::ALIAS_ACTION_VALIDERS,
                UserRepository::ALIAS_ACTION_VALIDERS,
                CorbeilleRepository::ALIAS_ACTION_READERS
            )
            ->leftJoin(self::ALIAS . '.deployements', DeployementRepository::ALIAS)
            ->leftJoin(DeployementRepository::ALIAS . '.organisme', OrganismeRepository::ALIAS)
            ->leftJoin(DeployementRepository::ALIAS . '.writers', CorbeilleRepository::ALIAS_DEPLOYEMENT_WRITERS)
            ->leftJoin(self::ALIAS . '.indicators', IndicatorRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_ACTION_WRITERS . '.users', UserRepository::ALIAS_ACTION_WRITERS)
            ->leftJoin(self::ALIAS . '.validers', CorbeilleRepository::ALIAS_ACTION_VALIDERS)
            ->leftJoin(CorbeilleRepository::ALIAS_ACTION_VALIDERS . '.users', UserRepository::ALIAS_ACTION_VALIDERS)
            ->leftJoin(self::ALIAS . '.readers', CorbeilleRepository::ALIAS_ACTION_READERS);
    }

    private function initialise_select_for_rqt_ajax()
    {
        $this->initialise_select();
        $this->builder
            ->addSelect(
                CorbeilleRepository::ALIAS_ACTION_WRITERS,
                UserRepository::ALIAS_ACTION_WRITERS,
                CorbeilleRepository::ALIAS_ACTION_VALIDERS,
                UserRepository::ALIAS_ACTION_VALIDERS,
                CorbeilleRepository::ALIAS_ACTION_READERS
            )
            ->leftJoin(self::ALIAS . '.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS)
            ->leftJoin(CorbeilleRepository::ALIAS_ACTION_WRITERS . '.users', UserRepository::ALIAS_ACTION_WRITERS)
            ->leftJoin(self::ALIAS . '.validers', CorbeilleRepository::ALIAS_ACTION_VALIDERS)
            ->leftJoin(CorbeilleRepository::ALIAS_ACTION_VALIDERS . '.users', UserRepository::ALIAS_ACTION_VALIDERS)
            ->leftJoin(self::ALIAS . '.readers', CorbeilleRepository::ALIAS_ACTION_READERS);
    }

    private function initialise_select_for_table()
    {
        $this->initialise_select();
        $this->initialise_select();
        $this->builder
            ->addSelect(CorbeilleRepository::ALIAS_ACTION_WRITERS)
            ->leftJoin(self::ALIAS . '.writers', CorbeilleRepository::ALIAS_ACTION_WRITERS);
    }

    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(AxeRepository::ALIAS . '.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS . '.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS . '.ref', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS . '.name', 'ASC')
            ->orderBy(CategoryRepository::ALIAS . '.ref', 'ASC')
            ->orderBy(CategoryRepository::ALIAS . '.name', 'ASC')
            ->orderBy(self::ALIAS . '.ref ', 'ASC')
            ->orderBy(self::ALIAS . '.name ', 'ASC');
    }

    public function findAllForDto(ActionSearchDto $dto, string $filtre)
    {
        $this->dto = $dto;

        switch ($filtre) {
            case self::FILTRE_DTO_INIT_TABLEAU:
                $this->initialise_select_for_table();
                break;
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $this->initialise_select_for_unitaire();
                break;
            case self::FILTRE_DTO_INIT_AJAX:
                $this->initialise_select_for_rqt_ajax();
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $this->initialise_select_for_search();
                break;
        }

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    private function initialise_where()
    {
        $this->initialise_where_id();

        $this->initialise_where_user_writer();

        $this->initialise_where_user_valider();

        $this->initialise_where_jalon();

        $this->initialise_where_state();

        $this->initialise_where_states();

        $this->initialise_where_axe();
        $this->initialise_where_pole();
        $this->initialise_where_thematique();
        $this->initialise_where_category();

        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }
    }

    private function initialise_where_id()
    {
        $dto = $this->dto;
        if (empty($dto->getId())) {
            $this->builder
                ->where(AxeRepository::ALIAS . '.enable=' . ($dto->isAxeEnable() ? 'true' : 'false'))
                ->andwhere(PoleRepository::ALIAS . '.enable=' . ($dto->isPoleEnable() ? 'true' : 'false'))
                ->andwhere(ThematiqueRepository::ALIAS . '.enable=' . ($dto->isThematiqueEnable() ? 'true' : 'false'))
                ->andwhere(CategoryRepository::ALIAS . '.enable=' . ($dto->isCategoryEnable() ? 'true' : 'false'))
                ->andwhere(AxeRepository::ALIAS . '.archiving=' . ($dto->isActionArchiving() ? 'true' : 'false'));
        } else {
            $this->builder->andwhere(self::ALIAS . '.id = :id');
            $this->addParams('id', $dto->getId());
        }
    }

    private function initialise_where_user_writer()
    {
        if (!empty($this->dto->getUserWriter())) {
            $this->builder->andwhere(UserRepository::ALIAS_ACTION_WRITERS . '.id = :useridw');
            $this->addParams('useridw', $this->dto->getUserWriter());
        }
    }

    private function initialise_where_user_valider()
    {
        if (!empty($this->dto->getUserValider())) {
            $this->builder->andwhere(UserRepository::ALIAS_ACTION_VALIDERS . '.id = :useridv');
            $this->addParams('useridv', $this->dto->getUserValider());
        }
    }

    private function initialise_where_jalon()
    {
        $dto=$this->dto;
        $builder=$this->builder;

        if ($dto->getJalonNotPresentValider() || $dto->getJalonNotPresentWriter()) {
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

    private function initialise_where_state()
    {
        if (!empty($this->dto->getState())) {
            $this->builder->andwhere(self::ALIAS . '.state = :state');
            $this->addParams('state', $this->dto->getState());
        }
    }

    private function initialise_where_states()
    {
        if (!empty($this->dto->getStates())) {
            $this->builder->andwhere(self::ALIAS . '.state in (:state)');

            $this->addParams('state', $this->dto->getStates());
        }
    }

    private function initialise_where_axe()
    {
        if (!empty($this->dto->getAxeId())) {
            $this->builder->andwhere(AxeRepository::ALIAS . '.id = :axeid');
            $this->addParams('axeid', $this->dto->getAxeId());
        }
    }
    private function initialise_where_pole()
    {
        if (!empty($this->dto->getPoleId())) {
            $this->builder->andwhere(PoleRepository::ALIAS . '.id = :poleid');
            $this->addParams('poleid', $this->dto->getPoleId());
        }
    }
    private function initialise_where_thematique()
    {
        if (!empty($this->dto->getThematiqueId())) {
            $this->builder->andwhere(ThematiqueRepository::ALIAS . '.id = :thematiqueid');
            $this->addParams('thematiqueid', $this->dto->getThematiqueId());
        }
    }
    private function initialise_where_category()
    {
        if (!empty($this->dto->getCategoryId())) {
            $this->builder->andwhere(CategoryRepository::ALIAS . '.id = :categoryid');
            $this->addParams('categoryid', $this->dto->getCategoryId());
        }
    }

    private function initialise_where_search()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (!empty($dto->getActionRef())) {
            if ('*' != $dto->getActionRef()) {
                $builder->andwhere(self::ALIAS . '.ref = :actionref');
                $this->addParams('actionref', $dto->getActionRef());
            }
            if ('*' != $dto->getCategoryRef()) {
                $builder->andwhere(CategoryRepository::ALIAS . '.ref = :categoryref');
                $this->addParams('categoryref', $dto->getCategoryRef());
            }
            if ('*' != $dto->getThematiqueRef()) {
                $builder->andwhere(ThematiqueRepository::ALIAS . '.ref = :thematiqueref');
                $this->addParams('thematiqueref', $dto->getThematiqueRef());
            }
        } elseif (!empty($dto->getSearch())) {
            $builder
                ->andwhere(self::ALIAS . '.name like :search')
                ->orWhere(self::ALIAS . '.content like :search')
                ->orWhere(self::ALIAS . '.cadrage like :search')
                ->orWhere(IndicatorRepository::ALIAS . '.name like :search')
                ->orWhere(IndicatorRepository::ALIAS . '.content like :search')
                ->orWhere(CategoryRepository::ALIAS . '.name like :search')
                ->orWhere(ThematiqueRepository::ALIAS . '.name like :search')
                ->orWhere(PoleRepository::ALIAS . '.name like :search')
                ->orWhere(AxeRepository::ALIAS . '.name like :search');

            $this->addParams('search', '%' . $dto->getSearch() . '%');
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
