<?php
declare(strict_types=1);

namespace App\Chatbot\Entity;

use DateTimeImmutable;

final class ChatMessage
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        private readonly string $role,
        private readonly string $content,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private readonly array $metadata = []
    ) {
    }

    public function role(): string
    {
        return $this->role;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * @return array{role:string, content:string, metadata:array<string, mixed>, createdAt:string}
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
            'metadata' => $this->metadata,
            'createdAt' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}
