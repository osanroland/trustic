<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function hasReviewFromEmail(string $email, string $companyName): bool
    {
        return null !== $this->createQueryBuilder('r')
            ->select('1')
            ->where('r.authorEmail = :email')
            ->andWhere('r.companyName = :companyName')
            ->setParameter('email', $email)
            ->setParameter('companyName', $companyName)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getCompanyStatistics(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.companyName, COUNT(r.id) AS count, AVG(r.rating) AS avgRating')
            ->groupBy('r.companyName')
            ->orderBy('avgRating', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function save(Review $review): void
    {
        $this->getEntityManager()->persist($review);
        $this->getEntityManager()->flush();
    }

    public function findPaginatedList(int $page, int $limit, ?string $search = null): Paginator
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if (null !== $search && '' !== $search) {
            $qb->andWhere('LOWER(r.companyName) LIKE LOWER(:search)')
                ->setParameter('search', '%'.$search.'%');
        }

        return new Paginator($qb);
    }
}
