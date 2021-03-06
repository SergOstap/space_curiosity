<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NearEarthObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function getHazardousPaginated(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('o')
            ->andWhere('o.isHazardous = :isHazardous')
            ->setParameter('isHazardous', true)
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(self::PER_PAGE_LIMIT)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($query);
    }

    public function getOneFastest(?bool $isHazardous): ?NearEarthObject
    {
        return $this->setHazardous($this->createQueryBuilder('o'), $isHazardous)
            ->orderBy('o.speed', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getBestMonth(?bool $isHazardous): ?array
    {
        return $this->setHazardous($this->createQueryBuilder('o'), $isHazardous)
            ->select('count(o.id) as count', 'substring(o.approachDate, 1, 7) as month')
            ->groupBy('month')
            ->orderBy('count', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
    }

    private function setHazardous(QueryBuilder $query, ?bool $isHazardous): QueryBuilder
    {
        if ($isHazardous !== null) {
            $query
                ->andWhere('o.isHazardous = :isHazardous')
                ->setParameter('isHazardous', $isHazardous);
        }

        return $query;
    }
}
