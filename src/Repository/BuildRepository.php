<?php

namespace App\Repository;

use App\Entity\Build;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Builds>
 *
 * @method Builds|null find($id, $lockMode = null, $lockVersion = null)
 * @method Builds|null findOneBy(array $criteria, array $orderBy = null)
 * @method Builds[]    findAll()
 * @method Builds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Build::class);
    }
}
