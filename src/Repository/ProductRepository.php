<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByQuery(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->orWhere('p.description LIKE :query') // Adapté à ton entité actuelle
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
