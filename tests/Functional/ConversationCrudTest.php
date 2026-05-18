<?php
declare(strict_types=1);

namespace Tests\Functional;

use Tests\Support\ChatbotTestCase;

final class ConversationCrudTest extends ChatbotTestCase
{
    public function testConversationCrudLifecycle(): void
    {
        $conversation = $this->service->createConversation(12, 'Weekly reading check-in');
        $this->assertSame(12, $conversation->userId());
        $this->assertSame('Weekly reading check-in', $conversation->title());

        $result = $this->service->handleMessage(12, $conversation->id(), 'Hello Lumo');
        $history = $this->service->getConversationHistory(12, $conversation->id());

        $this->assertSame($conversation->id(), $result['conversationId']);
        $this->assertCount(2, $history);
        $this->assertSame('user', $history[0]['role']);
        $this->assertSame('assistant', $history[1]['role']);

        $updated = $this->service->updateConversationTitle(12, $conversation->id(), 'Updated title');
        $this->assertSame('Updated title', $updated->title());

        $this->service->deleteConversation(12, $conversation->id());
        $this->assertNull($this->repository->find($conversation->id()));
    }

    public function testConversationCanBeCreatedFromFirstMessage(): void
    {
        $result = $this->service->handleMessage(44, null, 'Recommend a fantasy book for tonight');

        $conversation = $this->repository->find($result['conversationId']);
        $this->assertNotNull($conversation);
        $this->assertSame('Recommend a fantasy book for tonight', $conversation->title());
        $this->assertNotEmpty($result['reply']);
    }
}
