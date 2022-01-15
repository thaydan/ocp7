<?php

namespace App\Repository;

use App\Entity\Pagination;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllPaginated(int $page = 0, int $nbElementsPerPage = 20)
    {
        $q = $this->createQueryBuilder('product')
            ->setFirstResult($page * $nbElementsPerPage)
            ->setMaxResults($nbElementsPerPage);

        return new Pagination($q);
    }
}
