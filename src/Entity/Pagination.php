<?php

namespace App\Entity;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\Serializer\Annotation\Groups;

class Pagination
{
    #[Groups(["read:all"])]
    private array $elements;
    #[Groups(["read:all"])]
    private int $nbElements;
    #[Groups(["read:all"])]
    private int $nbElementsPerPage;
    #[Groups(["read:all"])]
    private int $nbPages;
    #[Groups(["read:all"])]
    private int $page;

    public function __construct(Query|QueryBuilder $q)
    {
        $paginator = new Paginator($q);

        $this->elements = iterator_to_array($paginator);
        $this->nbElements = count($paginator);
        $this->nbElementsPerPage = $q->getMaxResults();
        $this->nbPages = ceil($this->nbElements / $this->nbElementsPerPage);
        $this->page = $q->getFirstResult() / $this->nbElementsPerPage;
    }

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function getNbElements(): int
    {
        return $this->nbElements;
    }

    /**
     * @return int
     */
    public function getNbElementsPerPage(): int
    {
        return $this->nbElementsPerPage;
    }

    /**
     * @return int
     */
    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    /**
     * @return float|int
     */
    public function getPage(): float|int
    {
        return $this->page;
    }
}