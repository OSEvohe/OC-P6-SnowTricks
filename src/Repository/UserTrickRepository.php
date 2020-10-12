<?php

namespace App\Repository;

use App\Entity\UserTrick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTrick|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTrick|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTrick[]    findAll()
 * @method UserTrick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTrick::class);
    }

    // /**
    //  * @return UserTrick[] Returns an array of UserTrick objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTrick
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
