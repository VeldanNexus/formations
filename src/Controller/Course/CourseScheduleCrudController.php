<?php

namespace App\Controller\Course;

use App\Entity\CourseSchedule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;


class CourseScheduleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseSchedule::class;
    }

 
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('course'),
            DateTimeField::new('startTime'),
            DateTimeField::new('endTime'),
        ];
    }
     
}
