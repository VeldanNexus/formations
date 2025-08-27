<?php
// src/Controller/CourseSubscriptionController.php
namespace App\Controller\Course;

use App\Entity\Course;
use App\Repository\CourseRepository;
use App\Service\CourseSubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
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
        return $this->redirectToRoute('app_my_courses');
    }

    #[Route('/courses/{id}/unsubscribe', name: 'course_unsubscribe', methods: ['POST'])]
    public function unsubscribe(Course $course, Request $request, CourseSubscriptionManager $manager): RedirectResponse
    {
        $this->isCsrfTokenValid('unsubscribe_'.$course->getId(), $request->request->get('_token')) || $this->createAccessDeniedException();

        $manager->cancel($this->getUser(), $course);
        $this->addFlash('info', 'Subscription canceled.');
        return $this->redirectToRoute('app_my_courses');
    }

    #[Route('/courses/{id}/edit-subscription', name: 'course_edit_subscription', methods: ['GET', 'POST'])]
    public function editSubscription(
        Course $course,
        Request $request,
        CourseRepository $courseRepository,
        CourseSubscriptionManager $manager
    ): Response {
        $user = $this->getUser();

        // Get all courses except the current one
        $availableCourses = $courseRepository->createQueryBuilder('c')
            ->andWhere('c.id != :current')
            ->setParameter('current', $course->getId())
            ->getQuery()
            ->getResult();

        if ($request->isMethod('POST')) {
            $newCourseId = $request->request->get('new_course');
            $newCourse = $courseRepository->find($newCourseId);

            if ($newCourse) {
                // Update subscription
                $manager->update($user, $course, $newCourse);
                $this->addFlash('success', 'Subscription updated successfully!');
                return $this->redirectToRoute('app_my_courses');
            }
        }

        return $this->render('course/edit_course_subscription.html.twig', [
            'currentCourse' => $course,
            'availableCourses' => $availableCourses,
        ]);
    }
}
