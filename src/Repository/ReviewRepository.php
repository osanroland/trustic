<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return array
     */
    public function getCompanyStatistics(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.companyName, COUNT(r.id) AS count, AVG(r.rating) AS avgRating')
            ->groupBy('r.companyName')
            ->orderBy('avgRating', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    /** @return Review[] */
    public function findAllOrderedByDate(?string $search = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC');

        if ($search !== null && $search !== '') {
            $qb->andWhere('LOWER(r.companyName) LIKE LOWER(:search)')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
