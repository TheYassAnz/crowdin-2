<?php

namespace App\Controller;

use App\Entity\Sources;
use App\Form\SourceType;
use App\Repository\SourcesRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sources')]
class SourcesController extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    #[Route('', name: 'app_sources')]
    function index(SourcesRepository $repository): Response
    {
        $sources  = $repository->findAll();
        return $this->render('sources/index.html.twig', [
            'controller_name' => 'SourcesController',
            'sources' => $sources,
        ]);
    }
    #[Route('/new', name: 'app_sources_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $source = new Sources();
        $form = $this->createForm(SourceType::class, $source);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($source);
            $entityManager->flush();

            // envoyé notification
            $this->notificationService->sendSourceCreationNotification($source);

            $this->addFlash('success', 'Source created successfully! Check your email for details.');
            return $this->redirectToRoute('app_sources');
        }

        return $this->render('sources/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_sources_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Sources $source): Response
    {
        return $this->render('sources/show.html.twig', [
            'source' => $source,
        ]);
    }
}
