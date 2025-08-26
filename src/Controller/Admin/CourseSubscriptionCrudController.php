<?php

namespace App\Controller\Admin;

use App\Entity\CourseSubscription;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class CourseSubscriptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseSubscription::class;
    }
   public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('course'),
            AssociationField::new('user'),
          
        ];
    }
}
