<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ProjectType;
use App\Repository\ProjectsRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects')]
class ProjectsController extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    #[Route('', name: 'app_projects')]
    public function index(ProjectsRepository $repository): Response
    {
        $projects  = $repository->findAll();
        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
            'projects' => $projects,
        ]);
    }

    #[Route('/new', name: 'app_projects_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projects = new Projects();
        $form = $this->createForm(ProjectType::class, $projects);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projects);
            $entityManager->flush();

            // envoyé notification
            $this->notificationService->sendProjectCreationNotification($projects);

            $this->addFlash('success', 'Project created successfully! Check your email for details.');
            return $this->redirectToRoute('app_projects');
        }

        return $this->render('projects/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projects_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Projects $project): Response
    {
        return $this->render('projects/show.html.twig', [
            'project' => $project,
        ]);
    }

    public function getProjectStats(ProjectsRepository $repository): array
    {
        $qb = $repository->createQueryBuilder('p')
            ->select('p.start_language as language, COUNT(p.id) as count')
            ->groupBy('p.start_language')
            ->getQuery();
        
        $results = $qb->getResult();
        
        return [
            'labels' => array_column($results, 'language'),
            'data' => array_column($results, 'count'),
        ];
    }
}
