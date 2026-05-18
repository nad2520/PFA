<?php
declare(strict_types=1);

namespace Tests\Support;

use App\Chatbot\ChatbotService;
use App\Chatbot\Infrastructure\InMemoryConversationRepository;
use App\Chatbot\Security\BiasGuard;
use App\Chatbot\Security\InputSanitizer;
use App\Chatbot\Security\PromptInjectionGuard;
use App\Chatbot\Security\SensitiveDataGuard;
use PHPUnit\Framework\TestCase;

abstract class ChatbotTestCase extends TestCase
{
    protected InMemoryConversationRepository $repository;
    protected FakeLlmClient $llmClient;
    protected FakeRecommendationProvider $recommendationProvider;
    protected ChatbotService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryConversationRepository();
        $this->llmClient = new FakeLlmClient();
        $this->recommendationProvider = new FakeRecommendationProvider();
        $this->service = $this->buildService();
    }

    protected function buildService(
        ?FakeLlmClient $llmClient = null,
        ?FakeRecommendationProvider $recommendationProvider = null,
        int $maxMessageLength = 4000
    ): ChatbotService {
        return new ChatbotService(
            $this->repository,
            $llmClient ?? $this->llmClient,
            $recommendationProvider ?? $this->recommendationProvider,
            new InputSanitizer($maxMessageLength),
            new PromptInjectionGuard(),
            new SensitiveDataGuard(),
            new BiasGuard()
        );
    }

    protected function similarityScore(string $left, string $right): float
    {
        $leftWords = $this->normalizedWords($left);
        $rightWords = $this->normalizedWords($right);
        $union = array_unique(array_merge($leftWords, $rightWords));
        if ($union === []) {
            return 1.0;
        }

        $intersection = array_intersect($leftWords, $rightWords);

        return count($intersection) / count($union);
    }

    /**
     * @return list<string>
     */
    private function normalizedWords(string $text): array
    {
        $normalized = mb_strtolower($text);
        $normalized = preg_replace('/[^a-z0-9 ]/i', ' ', $normalized) ?? '';
        $words = preg_split('/\s+/u', trim($normalized)) ?: [];

        return array_values(array_filter($words, static fn (string $word): bool => $word !== ''));
    }
}
