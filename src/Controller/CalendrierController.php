<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CourseSchedule;
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
    {   $courseSchedules = $entityManager->getRepository(CourseSchedule::class)->findAll();

             

         $events = [];
                          
              
        $events = [];
        
        foreach ($courseSchedules as $schedule) {
            $course = $schedule->getCourse();
            
            $events[] = [
                'id' => $schedule->getId(),
                'title' => $course ? $course->getTitle() : 'Unnamed Course', // Use course title
                'start' => $schedule->getStartTime()->format('c'), // ISO 8601 format
                'end' => $schedule->getEndTime()->format('c'),     // ISO 8601 format


              
            ];
        }
        
      

        return $this->json($events);
    }
}
