<?php

namespace App\Controller\Course;

use App\Entity\Course;
use App\Repository\CourseRepository;
use App\Repository\CourseSubscriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoursesController extends AbstractController
{
    #[Route('/my-courses', name: 'app_my_courses')]
    public function myCourses(CourseSubscriptionRepository $subscriptionRepo): Response
    {
        $user = $this->getUser();
        $subscriptions = $subscriptionRepo->findByUser($user);
        $courses = array_map(fn($sub) => $sub->getCourse(), $subscriptions);

        return $this->render('course/user_courses.html.twig', [
            'courses' => $courses
        ]);
    }

    #[Route('/course', name: 'app_course', methods: ['GET'])]
    public function index(
        Request $request,
        CourseRepository $courseRepo,
        CourseSubscriptionRepository $subscriptionRepo,
        PaginatorInterface $paginator
    ): Response {
        $user = $this->getUser();
        $q    = $request->query->get('q');

        $qb = $courseRepo->createFilteredQB($q);

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            5
        );

        // Get IDs of courses user is subscribed to
        $subscriptions = $subscriptionRepo->findByUser($user);
        $subscribedCourseIds = array_map(fn($sub) => $sub->getCourse()->getId(), $subscriptions);

        return $this->render('course/index.html.twig', [
            'courses' => $pagination,
            'q' => $q,
            'subscribedCourseIds' => $subscribedCourseIds
        ]);
    }

    #[Route('/courses', name: 'course_index')]
    public function allCourses(CourseRepository $courseRepository, CourseSubscriptionRepository $subscriptionRepo): Response
    {
        $courses = $courseRepository->findAll();
        $user = $this->getUser();

        // IDs of subscribed courses
        $subscriptions = $subscriptionRepo->findByUser($user);
        $subscribedCourseIds = array_map(fn($sub) => $sub->getCourse()->getId(), $subscriptions);

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'subscribedCourseIds' => $subscribedCourseIds
        ]);
    }
}
