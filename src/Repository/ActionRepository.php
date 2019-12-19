<?php

namespace App\Repository;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
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

    public function findAllForDto(ActionSearchDto $dto)
    {
        $params = [];

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
            ->leftjoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS);

        $builder
            ->where(AxeRepository::ALIAS.'.enable='.($dto->getAxeEnable() ? 'true' : 'false'))
            ->andwhere(PoleRepository::ALIAS.'.enable='.($dto->getPoleEnable() ? 'true' : 'false'))
            ->andwhere(ThematiqueRepository::ALIAS.'.enable='.($dto->getThematiqueEnable() ? 'true' : 'false'))
            ->andwhere(CategoryRepository::ALIAS.'.enable='.($dto->getCategoryEnable() ? 'true' : 'false'))
            ->andwhere(AxeRepository::ALIAS.'.archiving='.($dto->getActionArchiving() ? 'true' : 'false'));

        if (!empty($dto->getAxeId())) {
            $builder->where(AxeRepository::ALIAS.'.id = :axeid');

             $params=$this->addParams($params,'axeid', $dto->getAxeId());
        }


        if (!empty($dto->getActionRef())) {
            if ('*' != $dto->getActionRef()) {
                $builder->andwhere(self::ALIAS.'.ref = :actionref');
                $params=$this->addParams($params,'actionref', $dto->getActionRef());
            }
            if ('*' != $dto->getCategoryRef()) {
                $builder->andwhere(CategoryRepository::ALIAS.'.ref = :categoryref');
                $params=$this->addParams($params,'categoryref', $dto->getCategoryRef());
            }
            if ('*' != $dto->getThematiqueRef()) {
                $builder->andwhere(ThematiqueRepository::ALIAS.'.ref = :thematiqueref');
                $params=$this->addParams($params,'thematiqueref', $dto->getThematiqueRef());
            }
        } elseif (!empty($dto->getSearch())) {
            $builder
                ->andwhere(self::ALIAS.'.name like :search')
                ->orWhere(self::ALIAS.'.content like :search')
                ->orWhere(self::ALIAS.'.cadrage like :search')
                ->orWhere(CategoryRepository::ALIAS.'.name like :search')
                ->orWhere(ThematiqueRepository::ALIAS.'.name like :search')
                ->orWhere(PoleRepository::ALIAS.'.name like :search')
                ->orWhere(AxeRepository::ALIAS.'.name like :search');

            $params=$this->addParams($params,'search', '%'.$dto->getSearch().'%');
        }

        if (count( $params) >0) {
            $builder->setParameters($params);
        }

        $builder
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(CategoryRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(self::ALIAS.'.ref ', 'ASC')
            ->orderBy(self::ALIAS.'.name ', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }

    private function addParams($params, $key,$value): array
    {
        $onevalue=[ $key=>$value];
        if(count($params)==0) {
            return $onevalue;
        } else {
            $total=array_merge($onevalue, $params);
            return $total;
        }
    }
}
