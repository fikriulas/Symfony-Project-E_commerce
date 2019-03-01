<?php

namespace App\Repository;

use App\Entity\Userlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Userlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userlist[]    findAll()
 * @method Userlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserlistRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Userlist::class);
    }

    // /**
    //  * @return Userlist[] Returns an array of Userlist objects
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
    public function findOneBySomeField($value): ?Userlist
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
