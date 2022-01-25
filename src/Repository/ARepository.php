<?php

namespace App\Repository;

use App\Entity\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ARepository extends ServiceEntityRepository
{
    public function findAllPaginated(int $page = 0, int $nbElementsPerPage = 20): Pagination
    {
        $q = $this->createQueryBuilder('a')
            ->setFirstResult($page * $nbElementsPerPage)
            ->setMaxResults($nbElementsPerPage);

        return new Pagination($q);
    }
}