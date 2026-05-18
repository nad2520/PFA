<?php
declare(strict_types=1);

namespace Tests\AI;

use Tests\Support\ChatbotTestCase;

final class ChatbotAiQualityTest extends ChatbotTestCase
{
    public function testResponseAccuracyAndRelevanceForRecommendationQueries(): void
    {
        $conversation = $this->service->createConversation(20, 'AI relevance');
        $result = $this->service->handleMessage(20, $conversation->id(), 'Suggest a fantasy book with strong world-building.');

        $this->assertStringContainsString('The Name of the Wind', $result['reply']);
        $this->assertStringContainsStringIgnoringCase('match', $result['reply']);
        $this->assertSame('fantasy', $result['recommendations'][0]['genre']);
    }

    public function testAmbiguousQueriesInviteClarification(): void
    {
        $conversation = $this->service->createConversation(21, 'Ambiguous');
        $result = $this->service->handleMessage(21, $conversation->id(), 'I want something good but I am not sure what.');

        $this->assertStringContainsStringIgnoringCase('do you want', $result['reply']);
        $this->assertStringContainsStringIgnoringCase('light', $result['reply']);
    }

    public function testOutOfScopeQueriesAreHandledGracefully(): void
    {
        $conversation = $this->service->createConversation(22, 'Out of scope');
        $result = $this->service->handleMessage(22, $conversation->id(), 'Tell me about quantum gardening on Mars.');

        $this->assertStringContainsStringIgnoringCase("don't have enough", $result['reply']);
        $this->assertStringContainsStringIgnoringCase('reframe', $result['reply']);
    }

    public function testBiasSensitiveQueriesReceiveInclusiveFallbacks(): void
    {
        $conversation = $this->service->createConversation(23, 'Bias');
        $result = $this->service->handleMessage(23, $conversation->id(), 'Recommend books for girls only.');

        $this->assertTrue($result['blocked']);
        $this->assertStringContainsStringIgnoringCase('genre', $result['reply']);
        $this->assertStringNotContainsStringIgnoringCase('girls are', $result['reply']);
    }

    public function testResponsesStayConsistentForTheSameInput(): void
    {
        $conversation = $this->service->createConversation(24, 'Consistency');
        $first = $this->service->handleMessage(24, $conversation->id(), 'How do I earn coins?');
        $second = $this->service->handleMessage(24, $conversation->id(), 'How do I earn coins?');

        $this->assertGreaterThanOrEqual(0.60, $this->similarityScore($first['reply'], $second['reply']));
    }

    public function testColdStartBehaviorRemainsHelpfulWithoutHistory(): void
    {
        $result = $this->service->handleMessage(25, null, 'Can you recommend something to read?');

        $this->assertNotEmpty($result['recommendations']);
        $this->assertStringContainsString('Project Hail Mary', $result['reply']);
        $this->assertNotEmpty($result['conversationId']);
    }
}
