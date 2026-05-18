<?php
declare(strict_types=1);

namespace Tests\Functional;

use App\Chatbot\Entity\ChatMessage;
use App\Chatbot\Entity\Conversation;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ConversationEntityTest extends TestCase
{
    public function testConversationAccessorsAndPayloadReflectStoredMessages(): void
    {
        $createdAt = new DateTimeImmutable('2026-04-28T10:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2026-04-28T10:05:00+00:00');
        $message = new ChatMessage('user', 'Hello', $createdAt, ['source' => 'fixture']);
        $conversation = new Conversation('conv_123', 7, 'Weekly recap', [$message], $createdAt, $updatedAt);

        $this->assertSame('conv_123', $conversation->id());
        $this->assertSame(7, $conversation->userId());
        $this->assertSame('Weekly recap', $conversation->title());
        $this->assertSame([$message], $conversation->messages());
        $this->assertSame($createdAt, $conversation->createdAt());
        $this->assertSame($updatedAt, $conversation->updatedAt());
        $this->assertSame([$message->toArray()], $conversation->messagePayload());
    }

    public function testConversationDefaultsUpdatedAtToCreatedAtAndMutationsRefreshIt(): void
    {
        $createdAt = new DateTimeImmutable('2026-04-28T08:00:00+00:00');
        $conversation = new Conversation('conv_edge', 0, '');

        $this->assertSame([], $conversation->messages());
        $this->assertLessThanOrEqual(1, abs($conversation->updatedAt()->getTimestamp() - $conversation->createdAt()->getTimestamp()));

        $beforeRename = $conversation->updatedAt();
        usleep(1000);
        $conversation->rename('Renamed conversation');
        $afterRename = $conversation->updatedAt();

        $this->assertSame('Renamed conversation', $conversation->title());
        $this->assertGreaterThanOrEqual($beforeRename->getTimestamp(), $afterRename->getTimestamp());

        usleep(1000);
        $message = new ChatMessage('assistant', 'Reply');
        $conversation->addMessage($message);

        $this->assertSame([$message], $conversation->messages());
        $this->assertGreaterThanOrEqual($afterRename->getTimestamp(), $conversation->updatedAt()->getTimestamp());
        $this->assertSame([$message->toArray()], $conversation->messagePayload());
    }
}
