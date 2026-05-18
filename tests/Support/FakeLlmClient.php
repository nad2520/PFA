<?php
declare(strict_types=1);

namespace Tests\Support;

use App\Chatbot\Contracts\LlmClientInterface;
use App\Chatbot\ValueObject\LlmResponse;

final class FakeLlmClient implements LlmClientInterface
{
    /**
     * @param array<string, string> $customResponses
     */
    public function __construct(
        private readonly array $customResponses = [],
        private readonly int $configuredLatencyMs = 0
    ) {
    }

    private int $callCount = 0;

    public function complete(array $messages, array $context = []): LlmResponse
    {
        $this->callCount++;
        if ($this->configuredLatencyMs > 0) {
            usleep($this->configuredLatencyMs * 1000);
        }

        $userMessage = '';
        for ($index = count($messages) - 1; $index >= 0; $index--) {
            if (($messages[$index]['role'] ?? '') === 'user') {
                $userMessage = (string) ($messages[$index]['content'] ?? '');
                break;
            }
        }

        $lower = mb_strtolower($userMessage);
        foreach ($this->customResponses as $needle => $response) {
            if (str_contains($lower, mb_strtolower($needle))) {
                return new LlmResponse($response, $this->configuredLatencyMs, ['matchedRule' => $needle]);
            }
        }

        $reply = $this->buildReply($lower, $context);

        return new LlmResponse($reply, $this->configuredLatencyMs);
    }

    public function callCount(): int
    {
        return $this->callCount;
    }

    /**
     * @param array<string, mixed> $context
     */
    private function buildReply(string $message, array $context): string
    {
        if (preg_match('/\b(hello|hi|hey)\b/i', $message) === 1) {
            return "Hello! I'm ready to help with reading suggestions, conversation history, and book questions.";
        }

        if (str_contains($message, 'coins')) {
            return 'Coins are earned by completing books, quests, and reading milestones.';
        }

        if (str_contains($message, 'history')) {
            return 'Your conversation history stays attached to this conversation so we can continue smoothly.';
        }

        if (str_contains($message, 'recommend') || str_contains($message, 'suggest')) {
            $recommendations = $context['recommendations'] ?? [];
            if ($recommendations !== []) {
                $titles = array_map(
                    static fn (array $book): string => $book['title'],
                    $recommendations
                );

                return 'Try ' . implode(', ', $titles) . '. I picked them because they match your interest and reading context.';
            }

            return 'I would start with a popular, approachable title and then refine the list once I learn your taste.';
        }

        if (str_contains($message, 'something good') || str_contains($message, 'not sure')) {
            return 'I can help with that. Do you want something light, adventurous, or more thoughtful?';
        }

        if (str_contains($message, 'quantum gardening on mars')) {
            return "I don't have enough grounded information on that topic. If you reframe it as a genre or theme, I can still help.";
        }

        if (str_contains($message, 'same input')) {
            return 'A steady response helps with trust, so I aim to stay consistent while still using the latest context.';
        }

        return 'I can help with recommendations, reading progress, and general book guidance.';
    }
}
