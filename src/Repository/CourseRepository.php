<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }
 public function findByUser($user): array
    {
        return $this->createQueryBuilder('cs')
            ->join('cs.course', 'c')
            ->addSelect('c')
            ->where('cs.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

   public function createFilteredQB(?string $q = null/*, ?string $category = null */): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.title', 'ASC');

        if ($q) {
            $qb->andWhere('c.title LIKE :q')
               ->setParameter('q', '%'.$q.'%');
        }

        // If you later have categories:
        // if ($category) {
        //     $qb->andWhere('c.category = :cat')->setParameter('cat', $category);
        // }

        return $qb;
    }

    //    /**
    //     * @return Course[] Returns an array of Course objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Course
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
