<?php

namespace App\Repository;

use App\Entity\HomeHero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomeHero>
 */
class HomeHeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeHero::class);
    }

    public function getContent(): ?HomeHero
    {
        return $this->findOneBy([]);
    }
}
