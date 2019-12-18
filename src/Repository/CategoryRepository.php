<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
            ->select(self::ALIAS, ThematiqueRepository::ALIAS, PoleRepository::ALIAS, AxeRepository::ALIAS)
            ->innerJoin(self::ALIAS.'.thematique', ThematiqueRepository::ALIAS)
            ->innerJoin(ThematiqueRepository::ALIAS.'.pole', PoleRepository::ALIAS)
            ->innerJoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
