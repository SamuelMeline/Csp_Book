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

    /**
     * Récupère les dernières panoplies publiques
     *
     * @param integer $nbBuilds
     * @return array
     */
    public function findPublicBuild(?int $nbBuilds): array
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->where('b.isPublic = 1')
            ->orderBy('b.createdAt', 'DESC');

        if ($nbBuilds !== 0 || !$nbBuilds !== null) {
            $queryBuilder->setMaxResults($nbBuilds);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
