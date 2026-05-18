<?php
declare(strict_types=1);

namespace Tests\Functional;

use App\Chatbot\Entity\ChatMessage;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ChatMessageEntityTest extends TestCase
{
    public function testAccessorsAndArraySerializationReturnOriginalValues(): void
    {
        $createdAt = new DateTimeImmutable('2026-04-28T16:45:00+01:00');
        $metadata = ['latencyMs' => 25, 'policy' => false];
        $message = new ChatMessage('assistant', 'A helpful reply', $createdAt, $metadata);

        $this->assertSame('assistant', $message->role());
        $this->assertSame('A helpful reply', $message->content());
        $this->assertSame($createdAt, $message->createdAt());
        $this->assertSame($metadata, $message->metadata());
        $this->assertSame(
            [
                'role' => 'assistant',
                'content' => 'A helpful reply',
                'metadata' => $metadata,
                'createdAt' => $createdAt->format(DATE_ATOM),
            ],
            $message->toArray()
        );
    }

    public function testEntitySupportsEdgeValuesWithoutLosingTypeSafety(): void
    {
        $message = new ChatMessage('user', '', new DateTimeImmutable('2026-04-28T00:00:00+00:00'));

        $this->assertSame('user', $message->role());
        $this->assertSame('', $message->content());
        $this->assertSame([], $message->metadata());
        $this->assertSame('', $message->toArray()['content']);
    }
}
