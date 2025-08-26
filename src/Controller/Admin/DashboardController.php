<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use  App\Controller\Course\CourseCrudController;
use App\Entity\Course;
use App\Entity\User;
use App\Entity\CourseSchedule;
use App\Entity\CourseSubscription;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
  
        
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(CourseCrudController::class)->generateUrl());
 
 
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Calendar App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Formation', 'fa-solid fa-book-open', Course::class);
        yield MenuItem::linkToCrud('Schedule', icon: 'fa-solid fa-stopwatch-20', entityFqcn: CourseSchedule::class);
                yield MenuItem::linkToCrud('Users', icon: 'fa-solid fa-user', entityFqcn:  User::class);
                   yield MenuItem::linkToCrud('Inscrtiptions', icon: 'fa-solid fa-list', entityFqcn:  CourseSubscription::class);

         yield MenuItem::linkToRoute('Calendar', 'fa-solid fa-calendar-days', routeName: 'calendar_page');
    }
}
