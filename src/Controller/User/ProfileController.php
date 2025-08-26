<?php
// src/Controller/ProfileController.php

namespace App\Controller\User;

 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_register');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}