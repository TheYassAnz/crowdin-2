<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ProjectType;
use App\Repository\ProjectsRepository;
use App\Service\NotificationService;
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
        $projects->setUser($this->getUser()); // Set current user before form creation

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

    #[Route('/{id}/delete', name: 'app_projects_delete', methods: ['POST'])]
    public function delete(Request $request, Projects $project, EntityManagerInterface $entityManager): Response
    {
        if ($project->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own projects.');
        }

        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
            $this->addFlash('success', 'Project deleted successfully.');
        }

        return $this->redirectToRoute('app_projects');
    }

    #[Route('/{id}/export-csv', name: 'app_projects_export_csv')]
    public function exportProjectCsv(Projects $project): Response
    {
        if ($project->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="project_' . $project->getId() . '.csv"');

        $output = fopen('php://temp', 'r+');

        // Headers
        fputcsv($output, ['Key', 'Content', 'Project']);

        // Data
        foreach ($project->getSources() as $source) {
            fputcsv($output, [
                $source->getCle(),
                $source->getContent(),
                $project->getName()
            ]);
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        $response->setContent($content);
        return $response;
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
