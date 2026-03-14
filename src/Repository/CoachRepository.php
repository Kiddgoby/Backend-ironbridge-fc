<?php

namespace App\Repository;

use App\Entity\Coach;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coach>
 */
class CoachRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coach::class);
    }

    /**
     * Devuelve todos los entrenadores de un grupo concreto, ordenados por apellido.
     *
     * @return Coach[]
     */
    public function findByGroup(string $group): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.group = :group')
            ->setParameter('group', $group)
            ->orderBy('c.surname', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
