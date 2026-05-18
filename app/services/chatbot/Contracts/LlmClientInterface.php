<?php
declare(strict_types=1);

namespace App\Chatbot\Contracts;

use App\Chatbot\ValueObject\LlmResponse;

interface LlmClientInterface
{
    /**
     * @param list<array{role:string, content:string, metadata?:array<string, mixed>}> $messages
     * @param array<string, mixed> $context
     */
    public function complete(array $messages, array $context = []): LlmResponse;
}
