<?php
declare(strict_types=1);

namespace Tests\Functional;

use App\Chatbot\Exceptions\ValidationException;
use Tests\Support\ChatbotTestCase;

final class ResponseHandlingTest extends ChatbotTestCase
{
    public function testCommonGreetingGetsHelpfulResponse(): void
    {
        $conversation = $this->service->createConversation(1, 'Greeting');
        $result = $this->service->handleMessage(1, $conversation->id(), 'Hello there');

        $this->assertStringContainsStringIgnoringCase('hello', $result['reply']);
        $this->assertStringContainsStringIgnoringCase('help', $result['reply']);
    }

    public function testRecommendationQueryReturnsRelevantBooks(): void
    {
        $conversation = $this->service->createConversation(2, 'Recommendations');
        $result = $this->service->handleMessage(2, $conversation->id(), 'Can you recommend a mystery book?');

        $this->assertNotEmpty($result['recommendations']);
        $this->assertStringContainsString('The Hound of the Baskervilles', $result['reply']);
        $this->assertStringContainsStringIgnoringCase('match', $result['reply']);
    }

    public function testEmptyMessagesAreRejected(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Message cannot be empty.');

        $this->service->handleMessage(3, null, '   ');
    }

    public function testVeryLongMessagesAreRejected(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Message exceeds the maximum length');

        $tooLong = str_repeat('a', 4001);
        $this->service->handleMessage(4, null, $tooLong);
    }

    public function testUnsupportedSpecialCharactersAreRejected(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('unsupported special characters');

        $this->service->handleMessage(5, null, "Hello\x00World");
    }
}
