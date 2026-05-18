<?php
declare(strict_types=1);

namespace App\Chatbot\ValueObject;

final class LlmResponse
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        private readonly string $content,
        private readonly int $latencyMs = 0,
        private readonly array $metadata = []
    ) {
    }

    public function content(): string
    {
        return $this->content;
    }

    public function latencyMs(): int
    {
        return $this->latencyMs;
    }

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}
