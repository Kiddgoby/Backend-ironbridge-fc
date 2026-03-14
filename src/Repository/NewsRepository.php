<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * Devuelve todas las noticias de una categoría concreta, ordenadas por fecha descendente.
     *
     * @return News[]
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.category = :category')
            ->setParameter('category', $category)
            ->orderBy('n.published_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Devuelve las noticias marcadas como destacadas, ordenadas por fecha descendente.
     *
     * @return News[]
     */
    public function findFeatured(): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.is_featured = :featured')
            ->setParameter('featured', true)
            ->orderBy('n.published_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
