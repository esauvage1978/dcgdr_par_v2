<?php

namespace App\Repository;

use App\Entity\ActionState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActionState|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionState|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionState[]    findAll()
 * @method ActionState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionStateRepository extends ServiceEntityRepository
{
    const ALIAS = 'ast';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionState::class);
    }

    public function findAllForAction(string $actionId)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ActionRepository::ALIAS,
                UserRepository::ALIAS)
            ->join( self::ALIAS.'.action', ActionRepository::ALIAS)
            ->join(self::ALIAS.'.user', UserRepository::ALIAS)
            ->where(self::ALIAS.'.action = :action')
            ->setParameter('action', $actionId)
            ->orderBy( self::ALIAS.'.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
