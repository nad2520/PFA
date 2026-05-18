<?php
declare(strict_types=1);

namespace App\Chatbot\Infrastructure;

use App\Chatbot\Contracts\ConversationRepositoryInterface;
use App\Chatbot\Entity\Conversation;

final class InMemoryConversationRepository implements ConversationRepositoryInterface
{
    /**
     * @var array<string, Conversation>
     */
    private array $conversations = [];

    public function nextIdentity(): string
    {
        return 'conv_' . bin2hex(random_bytes(8));
    }

    public function save(Conversation $conversation): void
    {
        $this->conversations[$conversation->id()] = $conversation;
    }

    public function find(string $conversationId): ?Conversation
    {
        return $this->conversations[$conversationId] ?? null;
    }

    public function delete(string $conversationId): void
    {
        unset($this->conversations[$conversationId]);
    }
}
