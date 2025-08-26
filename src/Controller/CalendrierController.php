<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CourseSchedule;
use App\Entity\CourseSubscription;
use Doctrine\ORM\EntityManagerInterface;
class CalendrierController extends AbstractController
{
    #[Route('/calendar', name: 'calendar_page')]
    public function index(): Response
    {
        return $this->render('calendar/page.html.twig');
    }
#[Route('/api/events', name: 'api_events')]
public function events(EntityManagerInterface $entityManager): JsonResponse
{
    // Get the authenticated user
    $userInterface = $this->getUser();
    if (!$userInterface) {
        throw $this->createAccessDeniedException('User not logged in');
    }

    // Fetch the full User entity from DB to get the ID
    $user = $entityManager
        ->getRepository(\App\Entity\User::class)
        ->findOneBy(['email' => $userInterface->getUserIdentifier()]); // assuming email is the identifier

    if (!$user) {
        throw $this->createAccessDeniedException('User not found');
    }

    $courseSchedules = [];

    $roles = $user->getRoles();
    $isUserRole = in_array('ROLE_USER', $roles, true);

    if ($isUserRole) {
        // Fetch only schedules of the courses the user is subscribed to
        $subscriptions = $entityManager
            ->getRepository(\App\Entity\CourseSubscription::class)
            ->createQueryBuilder('cs')
            ->join('cs.course', 'c')
            ->addSelect('c')
            ->where('cs.user = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();

        foreach ($subscriptions as $sub) {
            $course = $sub->getCourse();
            if ($course) {
                foreach ($course->getSchedules() as $schedule) {
                    $courseSchedules[] = $schedule;
                }
            }
        }
    } else {
        // Admin or other roles: all schedules
        $courseSchedules = $entityManager
            ->getRepository(\App\Entity\CourseSchedule::class)
            ->findAll();
    }

    $events = [];
    foreach ($courseSchedules as $schedule) {
        $course = $schedule->getCourse();

        $events[] = [
            'id'    => $schedule->getId(),
            'title' => $course ? $course->getTitle() : 'Unnamed Course',
            'start' => $schedule->getStartTime()->format('c'),
            'end'   => $schedule->getEndTime()->format('c'),
        ];
    }

    return new JsonResponse($events);
}



#[Route('/api/user/1/schedules', name: 'api_user_schedules')]
public function userSchedules(EntityManagerInterface $entityManager): JsonResponse
{
    $userId = 1; // fixed user id

    // Fetch the subscriptions for user ID 1
    $subscriptions = $entityManager
        ->getRepository(CourseSubscription::class)
        ->createQueryBuilder('cs')
        ->join('cs.course', 'c')
        ->addSelect('c')
        ->where('cs.user = :userId')
        ->setParameter('userId', $userId)
        ->getQuery()
        ->getResult();

    $courseSchedules = [];

    // Extract schedules from subscribed courses
    foreach ($subscriptions as $sub) {
        $course = $sub->getCourse();
        if ($course) {
            foreach ($course->getSchedules() as $schedule) {
                $courseSchedules[] = $schedule;
            }
        }
    }

    // Prepare events array
    $events = [];
    foreach ($courseSchedules as $schedule) {
        $course = $schedule->getCourse();

        $events[] = [
            'id'    => $schedule->getId(),
            'title' => $course ? $course->getTitle() : 'Unnamed Course',
            'start' => $schedule->getStartTime()->format('c'),
            'end'   => $schedule->getEndTime()->format('c'),
        ];
    }

    return new JsonResponse($events);
}


}
