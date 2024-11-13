<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/index.html.twig', parameters: [
            'user' => $user,
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function new(Request $request): Response
    {
        $profil = new Profil();
        $form = $this->createForm(ProfileType::class, $profil);

        return $this->render('profile/new.html.twig', [
            'form' => $form,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // faire quelque chose
        }
    }
}
