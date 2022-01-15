<?php

namespace App\Repository;

use App\Entity\ClientCustomer;
use App\Entity\Pagination;
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

    public function findAllPaginated(int $page = 0, int $nbElementsPerPage = 20)
    {
        $q = $this->createQueryBuilder('client_customer')
            ->setFirstResult($page * $nbElementsPerPage)
            ->setMaxResults($nbElementsPerPage);

        return new Pagination($q);
    }
}
