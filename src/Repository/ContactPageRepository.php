<?php

namespace App\Repository;

use App\Entity\ContactPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactPage>
 */
class ContactPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactPage::class);
    }

    public function getContent(): ?ContactPage
    {
        return $this->findOneBy([]);
    }
}
