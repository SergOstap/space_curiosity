<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NearEarthObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NearEarthObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method NearEarthObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method NearEarthObject[]    findAll()
 * @method NearEarthObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NearEarthObjectRepository extends ServiceEntityRepository
{
    public const PER_PAGE_LIMIT = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NearEarthObject::class);
    }
}
