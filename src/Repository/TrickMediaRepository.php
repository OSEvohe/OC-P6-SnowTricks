<?php

namespace App\Repository;

use App\Entity\TrickMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrickMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickMedia[]    findAll()
 * @method TrickMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickMedia::class);
    }

    // /**
    //  * @return TrickMedia[] Returns an array of TrickMedia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrickMedia
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
