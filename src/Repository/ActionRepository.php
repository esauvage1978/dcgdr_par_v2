<?php

namespace App\Repository;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            ->select(self::ALIAS .'.ref, ' . self::ALIAS . '.name')
            ->where(self::ALIAS . '.category = :cat')
            ->setParameter('cat', $categoryId)
            ->orderBy(self::ALIAS . '.ref', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllForDto(ActionSearchDto $dto)
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


            ->where(AxeRepository::ALIAS.'.enable=' . $dto->getAxeEnable() )
            ->andwhere(PoleRepository::ALIAS.'.enable='. $dto->getAxeEnable() )
            ->andwhere(ThematiqueRepository::ALIAS.'.enable='. $dto->getAxeEnable() )
            ->andwhere(CategoryRepository::ALIAS.'.enable='. $dto->getAxeEnable() )
            ->andwhere(self::ALIAS.'.enable='. $dto->getAxeEnable() );

        if(!empty($dto->getAxeId())) {
            $builder
                ->where(AxeRepository::ALIAS . '.id = :axeid')
                ->setParameter('axeid', $dto->getAxeId());
        }

        $builder
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->orderBy(ThematiqueRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(CategoryRepository::ALIAS.'.ref', 'ASC')
            ->orderBy(self::ALIAS.'.ref ','ASC')
            ->orderBy(self::ALIAS.'.name ','ASC');

        return $builder
            ->getQuery()
            ->getResult();


    }

}
