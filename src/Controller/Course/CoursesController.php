<?php

namespace App\Controller\Course;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CourseRepository;

class CoursesController extends AbstractController
{
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

    #[Route('/courses/{id}', name: 'course_show')]
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
            
        ]);
    }
}
