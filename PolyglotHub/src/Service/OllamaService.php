<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OllamaService
{
    private const OLLAMA_API_URL = 'http://localhost:11434/api/generate';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {}

    public function generateResponse(string $prompt, string $model = 'mistral'): string
    {
        try {
            $response = $this->httpClient->request('POST', self::OLLAMA_API_URL, [
                'json' => [
                    'model' => $model,
                    'prompt' => $prompt,
                    'stream' => false
                ]
            ]);

            $data = $response->toArray();
            return $data['response'] ?? 'No response generated';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function suggestTranslation(string $text, string $targetLanguage): string
    {
        $prompt = "Translate the following text to $targetLanguage: $text";
        return $this->generateResponse($prompt);
    }
}