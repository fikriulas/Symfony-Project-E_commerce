<?php

namespace App\Repository;

use App\Entity\Orderdetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Orderdetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orderdetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orderdetails[]    findAll()
 * @method Orderdetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderdetailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Orderdetails::class);
    }

    // /**
    //  * @return Orderdetails[] Returns an array of Orderdetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Orderdetails
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
