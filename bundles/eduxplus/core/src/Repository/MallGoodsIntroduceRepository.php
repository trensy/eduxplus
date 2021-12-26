<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\MallGoodsIntroduce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallGoodsIntroduce|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallGoodsIntroduce|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallGoodsIntroduce[]    findAll()
 * @method MallGoodsIntroduce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallGoodsIntroduceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallGoodsIntroduce::class);
    }

    // /**
    //  * @return MallGoodsIntroduce[] Returns an array of MallGoodsIntroduce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MallGoodsIntroduce
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}