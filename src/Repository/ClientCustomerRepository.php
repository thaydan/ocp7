<?php

namespace App\Repository;

use App\Entity\ClientCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientCustomer[]    findAll()
 * @method ClientCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientCustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientCustomer::class);
    }

    // /**
    //  * @return ClientCustomer[] Returns an array of ClientCustomer objects
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
    public function findOneBySomeField($value): ?ClientCustomer
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
