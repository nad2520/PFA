<?php
declare(strict_types=1);

namespace App\Chatbot\Contracts;

interface RecommendationProviderInterface
{
    /**
     * @param list<array{role:string, content:string, metadata?:array<string, mixed>}> $history
     * @return list<array{title:string, genre:string, reason:string}>
     */
    public function recommendForUser(int $userId, ?string $topic, int $limit, array $history = []): array;
}
