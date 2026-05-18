<?php
declare(strict_types=1);

namespace Tests\Performance;

use Tests\Support\ChatbotTestCase;
use Tests\Support\FakeLlmClient;

final class ChatbotPerformanceTest extends ChatbotTestCase
{
    public function testResponseTimeUnderSimulatedLoad(): void
    {
        $this->service = $this->buildService(new FakeLlmClient([], 0));

        $start = hrtime(true);
        for ($userId = 1; $userId <= 50; $userId++) {
            $conversation = $this->service->createConversation($userId, 'Load test ' . $userId);
            $this->service->handleMessage($userId, $conversation->id(), 'Recommend a fantasy book.');
            $this->service->handleMessage($userId, $conversation->id(), 'How do I earn coins?');
        }
        $elapsedMs = (int) ((hrtime(true) - $start) / 1_000_000);

        $this->assertLessThan(1500, $elapsedMs, 'Simulated concurrent load should stay responsive.');
    }

    public function testLlmLatencyIsMeasuredAndExposed(): void
    {
        $this->service = $this->buildService(new FakeLlmClient([], 25));
        $conversation = $this->service->createConversation(31, 'Latency');

        $result = $this->service->handleMessage(31, $conversation->id(), 'Hello');

        $this->assertGreaterThanOrEqual(20, $result['latencyMs']);
        $this->assertLessThan(150, $result['latencyMs']);
    }

    public function testLongInputsDoNotCauseNoticeablePerformanceDegradation(): void
    {
        $this->service = $this->buildService(new FakeLlmClient([], 0));
        $conversation = $this->service->createConversation(32, 'Long input');
        $longMessage = str_repeat('Please summarize this reading session and keep the advice practical. ', 45);

        $start = hrtime(true);
        $result = $this->service->handleMessage(32, $conversation->id(), $longMessage);
        $elapsedMs = (int) ((hrtime(true) - $start) / 1_000_000);

        $this->assertLessThan(250, $elapsedMs);
        $this->assertNotEmpty($result['reply']);
    }
}
