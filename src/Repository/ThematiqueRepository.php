<?php

namespace App\Repository;

use App\Entity\Thematique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
            ->select('a', self::ALIAS, PoleRepository::ALIAS, AxeRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.pole', PoleRepository::ALIAS)
            ->leftJoin(PoleRepository::ALIAS.'.axe', AxeRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->orderBy(AxeRepository::ALIAS.'.name', 'ASC')
            ->orderBy(PoleRepository::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
