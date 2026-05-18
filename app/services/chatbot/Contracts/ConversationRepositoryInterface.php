<?php
declare(strict_types=1);

namespace App\Chatbot\Contracts;

use App\Chatbot\Entity\Conversation;

interface ConversationRepositoryInterface
{
    public function nextIdentity(): string;

    public function save(Conversation $conversation): void;

    public function find(string $conversationId): ?Conversation;

    public function delete(string $conversationId): void;
}
