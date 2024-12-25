<?php

namespace App\Controller;

use App\Service\OllamaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/ai')]
class TranslationAIController extends AbstractController
{
    #[Route('/suggest', name: 'api_ai_suggest_translation', methods: ['POST'])]
    public function suggestTranslation(Request $request, OllamaService $ollama): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $text = $data['text'] ?? '';
        $targetLanguage = $data['targetLanguage'] ?? '';

        if (!$text || !$targetLanguage) {
            return $this->json(['error' => 'Missing required parameters'], 400);
        }

        $suggestion = $ollama->suggestTranslation($text, $targetLanguage);

        return $this->json(['suggestion' => $suggestion]);
    }
}
