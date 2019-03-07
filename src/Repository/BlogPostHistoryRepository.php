<?php

namespace App\Repository;

use App\Entity\BlogPostHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BlogPostHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPostHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPostHistory[]    findAll()
 * @method BlogPostHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostHistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogPostHistory::class);
    }

    // /**
    //  * @return BlogPostHistory[] Returns an array of BlogPostHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogPostHistory
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
