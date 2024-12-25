<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{

    #[Route('/profile/{id}', name: 'app_profile_show', requirements: ['id' => '\d+'])]
    public function detail(int $id, UserRepository $userRepository, ProfilRepository $profileRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find(id: $id);
        $profil = $entityManager->getRepository(Profil::class)->findOneBy(['user' => $user]);


        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'profil' => $profil,
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function new(Request $request, EntityManagerInterface $entityManager, SecurityController $security): Response
    {
        $user = $security->getUser();
        $profil = $entityManager->getRepository(Profil::class)->findOneBy(['user' => $user]);

        if (!$profil) {
            $profil = new Profil();
            $profil->setUser($user);
        }

        $form = $this->createForm(ProfileType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profil);
            $entityManager->flush();

            $this->addFlash('success', 'Profile successfuly updated !');

            return $this->redirectToRoute('home');
        }

        return $this->render('profile/new.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
