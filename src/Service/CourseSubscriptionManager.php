<?php
namespace App\Service;

use App\Entity\Course;
use App\Entity\CourseSubscription;
use App\Entity\User;
use App\Repository\CourseSubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class CourseSubscriptionManager
{
    public function __construct(
        private readonly CourseSubscriptionRepository $repo,
        private readonly EntityManagerInterface $em
    ) {}

    public function subscribe(User $user, Course $course): CourseSubscription
    {
        $sub = $this->repo->findOneByUserAndCourse($user, $course);

        if (!$sub) {
            $sub = (new CourseSubscription())
                ->setUser($user)
                ->setCourse($course);
            $this->em->persist(object: $sub);
        }

        

        $this->em->flush();
        return $sub;
    }

    public function cancel(User $user, Course $course): void
    {
        $sub = $this->repo->findOneByUserAndCourse($user, $course);
        if (!$sub) return;

    
        $this->em->flush();
    }

 

    public function isSubscribed(User $user, Course $course): bool
{
    return $this->repo->count(['user' => $user, 'course' => $course]) > 0;
}
}
