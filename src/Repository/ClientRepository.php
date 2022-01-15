<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findAllPaginated(int $page = 0, int $nbElementsPerPage = 20)
    {
        $q = $this->createQueryBuilder('client')
            ->setFirstResult($page * $nbElementsPerPage)
            ->setMaxResults($nbElementsPerPage);

        return new Pagination($q);
    }
}
