<?php

namespace App\Repository\Admin;

use App\Entity\Admin\messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method messages[]    findAll()
 * @method messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class messagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, messages::class);
    }

    // /**
    //  * @return messages[] Returns an array of messages objects
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
    public function findOneBySomeField($value): ?messages
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
