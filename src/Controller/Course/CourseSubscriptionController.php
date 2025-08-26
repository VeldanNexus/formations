<?php
// src/Controller/CourseSubscriptionController.php
namespace App\Controller\Course;

use App\Entity\Course;
use App\Service\CourseSubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CourseSubscriptionController extends AbstractController
{
    #[Route('/courses/{id}/subscribe', name: 'course_subscribe', methods: ['POST'])]
    public function subscribe(Course $course, Request $request, CourseSubscriptionManager $manager): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->isCsrfTokenValid('subscribe_'.$course->getId(), $request->request->get('_token')) || $this->createAccessDeniedException();

        $manager->subscribe($this->getUser(), $course);
        $this->addFlash('success', 'Subscribed successfully!');
        return $this->redirectToRoute('calendar_page', ['id' => $course->getId()]);
    }

    #[Route('/courses/{id}/unsubscribe', name: 'course_unsubscribe', methods: ['POST'])]
    public function unsubscribe(Course $course, Request $request, CourseSubscriptionManager $manager): RedirectResponse
    {
        $this->isCsrfTokenValid('unsubscribe_'.$course->getId(), $request->request->get('_token')) || $this->createAccessDeniedException();

        $manager->cancel($this->getUser(), $course);
        $this->addFlash('info', 'Subscription canceled.');
        return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
    }
}
