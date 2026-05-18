<?php
declare(strict_types=1);

namespace Tests\Support;

use App\Chatbot\Contracts\RecommendationProviderInterface;

final class FakeRecommendationProvider implements RecommendationProviderInterface
{
    private int $callCount = 0;

    public function recommendForUser(int $userId, ?string $topic, int $limit, array $history = []): array
    {
        $this->callCount++;

        $catalog = [
            'fantasy' => [
                ['title' => 'The Name of the Wind', 'genre' => 'fantasy', 'reason' => 'rich world-building'],
                ['title' => 'The Hobbit', 'genre' => 'fantasy', 'reason' => 'classic adventure pacing'],
                ['title' => 'Mistborn', 'genre' => 'fantasy', 'reason' => 'strong magic system'],
            ],
            'mystery' => [
                ['title' => 'The Hound of the Baskervilles', 'genre' => 'mystery', 'reason' => 'classic deductive tension'],
                ['title' => 'Gone Girl', 'genre' => 'mystery', 'reason' => 'twisty psychological suspense'],
                ['title' => 'The Silent Patient', 'genre' => 'mystery', 'reason' => 'fast modern intrigue'],
            ],
            'default' => [
                ['title' => 'Project Hail Mary', 'genre' => 'sci-fi', 'reason' => 'broad appeal for new readers'],
                ['title' => 'The Book Thief', 'genre' => 'historical', 'reason' => 'strong emotional storytelling'],
                ['title' => 'Atomic Habits', 'genre' => 'non-fiction', 'reason' => 'practical and easy to start'],
            ],
        ];

        $bucket = $catalog[$topic ?? 'default'] ?? $catalog['default'];

        return array_slice($bucket, 0, max(1, $limit));
    }

    public function callCount(): int
    {
        return $this->callCount;
    }
}
