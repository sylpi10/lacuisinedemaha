<?php

namespace App\Repository;

use App\Entity\FormulasHeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormulasHeader>
 */
class FormulasHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormulasHeader::class);
    }

    public function getContent(): ?FormulasHeader
    {
        return $this->findOneBy([]);
    }
}
