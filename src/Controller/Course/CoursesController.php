<?php

namespace App\Controller\Course;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CourseRepository;
use App\Repository\CourseSubscriptionRepository;

class CoursesController extends AbstractController
{

#[Route('/my-courses', name: 'app_my_courses')]
public function myCourses(CourseSubscriptionRepository $subscriptionRepo): Response
{
    $user = $this->getUser();

    // Use the custom repository method
    $subscriptions = $subscriptionRepo->findByUser($user);

    // Extract courses from subscriptions
    $courses = array_map(fn($sub) => $sub->getCourse(), $subscriptions);

    return $this->render('course/user_courses.html.twig', [
        'courses' => $courses
    ]);
}




    #[Route('/courses', name: 'course_index')]
    public function index(CourseRepository $courseRepository
      ): Response {
        $courses = $courseRepository->findAll();
                $user = $this->getUser();


    $isSubscribed = function (Course $course) use ($user) {
            
                return false;
            
        };
        

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'isSubscribed' => $isSubscribed
         
        ]);
    }
 
}
