<?php

namespace App\Controller;

use App\Entity\Sources;
use App\Entity\Translations;
use App\Form\TranslationType;
use App\Repository\TranslationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\SourcesRepository;
use App\Repository\LanguageRepository;
use App\Service\OllamaService;

class TranslationsController extends AbstractController
{
    public function __construct(
        private OllamaService $ollamaService,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('', name: 'app_translations')]
    function index(TranslationsRepository $repository): Response
    {
        $translations  = $repository->findAll();
        return $this->render('translations/index.html.twig', [
            'controller_name' => 'TranslationsController',
            'translations' => $translations,
        ]);
    }

    #[Route('/new', name: 'app_translations_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sourceId = $request->query->get('sourceId');
        $translation = new Translations();
        $source = $sourceId ? $entityManager->getRepository(Sources::class)->find($sourceId) : null;
        if ($source) {
            $translation->setSource($source);
        }
        $form = $this->createForm(TranslationType::class, $translation, ['source' => $source]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($translation);
            $entityManager->flush();
            return $this->redirectToRoute('app_sources_show', ['id' => $translation->getSource()->getId()]);
        }

        return $this->render('translations/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_translations_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Translations $translation): Response
    {
        return $this->render('translations/show.html.twig', [
            'translation' => $translation,
        ]);
    }

    #[Route('/translations/suggest', name: 'app_translations_suggest', methods: ['POST'])]
    public function suggest(
        Request $request, 
        SourcesRepository $sourcesRepo,
        LanguageRepository $languageRepo,
        OllamaService $ollamaService
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $source = $sourcesRepo->find($data['sourceId']);
            $targetLanguage = $languageRepo->find($data['targetLanguageId']);

            if (!$source || !$targetLanguage) {
                throw new \InvalidArgumentException('Source or target language not found');
            }

            $suggestion = $ollamaService->suggestTranslation(
                $source->getContent(),
                $targetLanguage->getCode()
            );

            return new JsonResponse(['suggestion' => $suggestion]);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()], 
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/translations/{id}/refresh', name: 'app_translations_refresh', methods: ['POST'])]
    public function refresh(Translations $translation, OllamaService $ollamaService): JsonResponse
    {
        $newTranslation = $ollamaService->suggestTranslation(
            $translation->getSource()->getContent(),
            $translation->getTargetLanguage()->getCode()
        );
        
        $translation->setTranslatedContent($newTranslation);
        $this->entityManager->flush();
        
        return new JsonResponse(['translation' => $newTranslation]);
    }
}
