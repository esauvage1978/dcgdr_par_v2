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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionState::class);
    }

    public function findAllForAction(string $actionId)
    {
        return $this->createQueryBuilder('ast')
            ->select('ast, a, u')
            ->join('ast.action', 'a')
            ->join('ast.user', 'u')
            ->where('ast.action = :action')
            ->setParameter('action', $actionId)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
