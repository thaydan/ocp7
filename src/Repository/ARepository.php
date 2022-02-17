<?php

namespace App\Repository;

use App\Entity\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class ARepository extends ServiceEntityRepository
{
    public function findAllPaginated(int $page = 0, int $nbElementsPerPage = 20, ?array $criteria = null): Pagination
    {
        $q = $this->createQueryBuilder('a');

        if ($criteria) {
            foreach ($criteria as $column => $value) {
                $q->andWhere("a.$column = :val")
                    ->setParameter('val', $value);
            }
        }

        $q->setFirstResult($page * $nbElementsPerPage)
            ->setMaxResults($nbElementsPerPage);

        return new Pagination($q);
    }
}