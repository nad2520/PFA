<?php
declare(strict_types=1);

namespace App\Chatbot\Entity;

use DateTimeImmutable;

final class Conversation
{
    /**
     * @param list<ChatMessage> $messages
     */
    public function __construct(
        private readonly string $id,
        private readonly int $userId,
        private string $title,
        private array $messages = [],
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private ?DateTimeImmutable $updatedAt = null
    ) {
        $this->updatedAt ??= $this->createdAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function rename(string $title): void
    {
        $this->title = $title;
        $this->touch();
    }

    public function addMessage(ChatMessage $message): void
    {
        $this->messages[] = $message;
        $this->touch();
    }

    /**
     * @return list<ChatMessage>
     */
    public function messages(): array
    {
        return $this->messages;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt ?? $this->createdAt;
    }

    /**
     * @return list<array{role:string, content:string, metadata:array<string, mixed>, createdAt:string}>
     */
    public function messagePayload(): array
    {
        return array_map(
            static fn (ChatMessage $message): array => $message->toArray(),
            $this->messages
        );
    }

    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
