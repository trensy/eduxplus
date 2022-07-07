<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\TeachChatForbid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachChatForbid|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachChatForbid|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachChatForbid[]    findAll()
 * @method TeachChatForbid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachChatForbidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachChatForbid::class);
    }

    // /**
    //  * @return TeachChatForbid[] Returns an array of TeachChatForbid objects
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
    public function findOneBySomeField($value): ?TeachChatForbid
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
