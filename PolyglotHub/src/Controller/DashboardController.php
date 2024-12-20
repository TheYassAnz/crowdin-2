<?php

namespace App\Controller;

use App\Repository\ProjectsRepository;
use App\Repository\SourcesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ProjectsRepository $projectsRepo, SourcesRepository $sourcesRepo): Response
    {
        $projectStats = $projectsRepo->getProjectStats();
        $sourceStats = $sourcesRepo->getSourceStats();

        return $this->render('dashboard/index.html.twig', [
            'projectStats' => $projectStats,
            'sourceStats' => $sourceStats,
        ]);
    }
}
