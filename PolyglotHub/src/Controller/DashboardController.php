<?php

namespace App\Controller;

use App\Repository\ProjectsRepository;
use App\Repository\SourcesRepository;
use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        ProjectsRepository $projectsRepo, 
        SourcesRepository $sourcesRepo,
        LanguageRepository $languageRepo
    ): Response {
        $projectStats = $projectsRepo->getProjectStats();
        $sourceStats = $sourcesRepo->getSourceStats();
        $translationStats = $sourcesRepo->getTranslationStats();
        $languageStats = $languageRepo->getLanguageStats();

        return $this->render('dashboard/index.html.twig', [
            'projectStats' => $projectStats,
            'sourceStats' => $sourceStats,
            'translationStats' => $translationStats,
            'languageStats' => $languageStats,
        ]);
    }
}
