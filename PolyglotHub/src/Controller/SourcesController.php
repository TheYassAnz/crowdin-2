<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Entity\Sources;
use App\Form\SourceType;
use App\Form\CsvImportType;
use App\Repository\SourcesRepository;
use App\Service\NotificationService;
use App\Service\SourceCsvService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sources')]
class SourcesController extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService,
        private readonly SourceCsvService $sourceCsvService
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
        $projectId = $request->query->get('projectId');
        $source = new Sources();
        $project = $projectId ? $entityManager->getRepository(Projects::class)->find($projectId) : null;
        if ($project) {
            $source->setProject($project);
        }
        $form = $this->createForm(SourceType::class, $source, ['project' => $project]);

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

    #[Route('/import-csv', name: 'app_sources_import_csv')]
    public function importCsv(Request $request): Response
    {
        $form = $this->createForm(CsvImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $project = $form->get('project')->getData();

            try {
                $sources = $this->sourceCsvService->importSources($file, $project);
                $this->addFlash('success', count($sources) . ' sources have been imported successfully.');
                return $this->redirectToRoute('app_sources');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error importing sources: ' . $e->getMessage());
            }
        }

        return $this->render('sources/import.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/export-csv', name: 'app_sources_export_csv')]
    public function exportCsv(SourcesRepository $sourcesRepository): Response
    {
        $sources = $sourcesRepository->findAll();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=sources.csv');

        $output = fopen('php://temp', 'r+');

        // Headers
        fputcsv($output, ['Key', 'Content', 'Project']);

        // Data
        foreach ($sources as $source) {
            fputcsv($output, [
                $source->getCle(),
                $source->getContent(),
                $source->getProject()->getName()
            ]);
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        $response->setContent($content);
        return $response;
    }

    #[Route('/{id}/delete', name: 'app_sources_delete', methods: ['POST'])]
    public function delete(Request $request, Sources $source, EntityManagerInterface $entityManager): Response
    {
        // Check if user owns the source through project
        if ($source->getProject()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own sources.');
        }

        if ($this->isCsrfTokenValid('delete' . $source->getId(), $request->request->get('_token'))) {
            $entityManager->remove($source);
            $entityManager->flush();
            $this->addFlash('success', 'Source deleted successfully.');
        }

        return $this->redirectToRoute('app_sources');
    }
}
