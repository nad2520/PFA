<?php
declare(strict_types=1);

namespace App\Chatbot;

use App\Chatbot\Contracts\ConversationRepositoryInterface;
use App\Chatbot\Contracts\LlmClientInterface;
use App\Chatbot\Contracts\RecommendationProviderInterface;
use App\Chatbot\Entity\ChatMessage;
use App\Chatbot\Entity\Conversation;
use App\Chatbot\Exceptions\UnauthorizedConversationAccessException;
use App\Chatbot\Security\BiasGuard;
use App\Chatbot\Security\InputSanitizer;
use App\Chatbot\Security\PromptInjectionGuard;
use App\Chatbot\Security\SensitiveDataGuard;

final class ChatbotService
{
    public function __construct(
        private readonly ConversationRepositoryInterface $repository,
        private readonly LlmClientInterface $llmClient,
        private readonly RecommendationProviderInterface $recommendationProvider,
        private readonly InputSanitizer $inputSanitizer = new InputSanitizer(),
        private readonly PromptInjectionGuard $promptInjectionGuard = new PromptInjectionGuard(),
        private readonly SensitiveDataGuard $sensitiveDataGuard = new SensitiveDataGuard(),
        private readonly BiasGuard $biasGuard = new BiasGuard()
    ) {
    }

    public function createConversation(int $userId, ?string $title = null): Conversation
    {
        $conversation = new Conversation(
            $this->repository->nextIdentity(),
            $userId,
            $this->normalizeTitle($title ?? 'New conversation')
        );
        $this->repository->save($conversation);

        return $conversation;
    }

    /**
     * @return list<array{role:string, content:string, metadata:array<string, mixed>, createdAt:string}>
     */
    public function getConversationHistory(int $userId, string $conversationId): array
    {
        return $this->getOwnedConversation($userId, $conversationId)->messagePayload();
    }

    public function updateConversationTitle(int $userId, string $conversationId, string $title): Conversation
    {
        $conversation = $this->getOwnedConversation($userId, $conversationId);
        $conversation->rename($this->normalizeTitle($title));
        $this->repository->save($conversation);

        return $conversation;
    }

    public function deleteConversation(int $userId, string $conversationId): void
    {
        $this->getOwnedConversation($userId, $conversationId);
        $this->repository->delete($conversationId);
    }

    /**
     * @return array{
     *   conversationId:string,
     *   reply:string,
     *   latencyMs:int,
     *   blocked:bool,
     *   recommendations:list<array{title:string, genre:string, reason:string}>
     * }
     */
    public function handleMessage(int $userId, ?string $conversationId, string $message): array
    {
        $sanitized = $this->inputSanitizer->sanitize($message);
        $this->inputSanitizer->validate($message, $sanitized);

        $conversation = $conversationId === null
            ? $this->createConversation($userId, $this->deriveTitleFromMessage($sanitized))
            : $this->getOwnedConversation($userId, $conversationId);

        $conversation->addMessage(new ChatMessage('user', $sanitized));

        if ($this->promptInjectionGuard->isMalicious($sanitized)) {
            return $this->finalizePolicyResponse(
                $conversation,
                "I can't follow requests to ignore safety rules or reveal hidden instructions.",
                true
            );
        }

        if ($this->biasGuard->shouldUseInclusiveFallback($sanitized)) {
            return $this->finalizePolicyResponse(
                $conversation,
                'Please share the genre, mood, or reading level you want. I avoid recommendations based on stereotypes or protected traits.',
                true
            );
        }

        $history = $conversation->messagePayload();
        $topic = $this->extractTopic($sanitized);
        $recommendations = $this->shouldFetchRecommendations($sanitized)
            ? $this->recommendationProvider->recommendForUser($userId, $topic, 3, $history)
            : [];

        $llmResponse = $this->llmClient->complete(
            $history,
            [
                'userId' => $userId,
                'topic' => $topic,
                'recommendations' => $recommendations,
            ]
        );

        $reply = $this->sensitiveDataGuard->redact($llmResponse->content());
        $conversation->addMessage(
            new ChatMessage(
                'assistant',
                $reply,
                metadata: ['latencyMs' => $llmResponse->latencyMs()] + $llmResponse->metadata()
            )
        );
        $this->repository->save($conversation);

        return [
            'conversationId' => $conversation->id(),
            'reply' => $reply,
            'latencyMs' => $llmResponse->latencyMs(),
            'blocked' => false,
            'recommendations' => $recommendations,
        ];
    }

    private function getOwnedConversation(int $userId, string $conversationId): Conversation
    {
        $conversation = $this->repository->find($conversationId);
        if ($conversation === null || $conversation->userId() !== $userId) {
            throw new UnauthorizedConversationAccessException('Conversation not found for this user.');
        }

        return $conversation;
    }

    private function normalizeTitle(string $title): string
    {
        $normalized = trim(preg_replace('/\s+/u', ' ', $title) ?? '');

        return $normalized === '' ? 'Untitled conversation' : $normalized;
    }

    private function deriveTitleFromMessage(string $message): string
    {
        $preview = mb_substr($message, 0, 40);

        return $this->normalizeTitle($preview === '' ? 'New conversation' : $preview);
    }

    private function shouldFetchRecommendations(string $message): bool
    {
        return preg_match('/recommend|suggest|what should i read|book/i', $message) === 1;
    }

    private function extractTopic(string $message): ?string
    {
        foreach (['fantasy', 'mystery', 'sci-fi', 'science fiction', 'romance', 'history'] as $topic) {
            if (stripos($message, $topic) !== false) {
                return $topic === 'science fiction' ? 'sci-fi' : $topic;
            }
        }

        return null;
    }

    /**
     * @return array{
     *   conversationId:string,
     *   reply:string,
     *   latencyMs:int,
     *   blocked:bool,
     *   recommendations:list<array{title:string, genre:string, reason:string}>
     * }
     */
    private function finalizePolicyResponse(Conversation $conversation, string $reply, bool $blocked): array
    {
        $conversation->addMessage(new ChatMessage('assistant', $reply, metadata: ['policy' => true]));
        $this->repository->save($conversation);

        return [
            'conversationId' => $conversation->id(),
            'reply' => $reply,
            'latencyMs' => 0,
            'blocked' => $blocked,
            'recommendations' => [],
        ];
    }
}
