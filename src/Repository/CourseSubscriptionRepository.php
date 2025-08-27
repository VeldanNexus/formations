<?php
// src/Repository/CourseSubscriptionRepository.php
namespace App\Repository;

use App\Entity\Course;
use App\Entity\CourseSubscription;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CourseSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseSubscription::class);
    }

    public function findOneByUserAndCourse(User $user, Course $course): ?CourseSubscription
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :u')->setParameter('u', $user)
            ->andWhere('s.course = :c')->setParameter('c', $course)
            ->getQuery()->getOneOrNullResult();
    }

 
}
