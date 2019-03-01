<?php

namespace App\Repository;

use App\Entity\Commentproduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Commentproduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentproduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentproduct[]    findAll()
 * @method Commentproduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentproductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Commentproduct::class);
    }

    // /**
    //  * @return Commentproduct[] Returns an array of Commentproduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commentproduct
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
