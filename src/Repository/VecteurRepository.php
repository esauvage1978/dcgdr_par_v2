<?php

namespace App\Repository;

use App\Entity\Vecteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Vecteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vecteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vecteur[]    findAll()
 * @method Vecteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VecteurRepository extends ServiceEntityRepository
{
    const ALIAS='v';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vecteur::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS, ActionRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
