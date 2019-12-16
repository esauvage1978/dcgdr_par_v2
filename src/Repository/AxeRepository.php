<?php

namespace App\Repository;

use App\Entity\Axe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Axe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Axe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Axe[]    findAll()
 * @method Axe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AxeRepository extends ServiceEntityRepository
{
    const AXE = 'a';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Axe::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::AXE)
            ->select(self::AXE, PoleRepository::POLE)
            ->leftJoin(self::AXE.'.poles', PoleRepository::POLE)
            ->orderBy(self::AXE.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function tauxRaz()
    {
        $queryBuilder = $this->createQueryBuilder(self::AXE);
        $queryBuilder->update(Axe::class, self::AXE)
            ->set(self::AXE.'.taux1 ', 0)
            ->set(self::AXE.'.taux2 ', 0);

        $query = $queryBuilder->getQuery();

        $query->getDQL();

        return $query->execute();
    }

    public function tauxCalcul()
    {
        $table_source='axe';
        $table_distante='pole';

        $sql = ' update '.$table_source.' '.self::AXE
            .' inner join ( '
            .' select '.$table_source.'_id, avg(taux1) as taux1, avg(taux2) as taux2, enable '
            .' from '.$table_distante.' where enable=true group by '.$table_source.'_id ) '.PoleRepository::POLE.' '
            .' on '.self::AXE.'.id='.PoleRepository::POLE.'.'.$table_source.'_id '
            .' set '.self::AXE.'.taux1='.PoleRepository::POLE.'.taux1, '
            .self::AXE.'.taux2='.PoleRepository::POLE.'.taux2 '
            .' where '.self::AXE.'.enable=true; ';

        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

            return $stmt->execute([]);
        } catch (DBALException $e) {
            return 'Error'.$e->getMessage();
        }
    }
}
