<?php

namespace App\Controller;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CourseRepository;

final class CourseController extends AbstractController
{
    #[Route('/course', name: 'app_course')]
    public function index(CourseRepository $courseRepository): Response
    {
        // Fetch all courses from the database
        $courses = $courseRepository->findAll();

        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
            'courses' => $courses, // pass courses to Twig
        ]);
    }
}

