<?php
declare(strict_types=1);

namespace App\Chatbot\Security;

final class BiasGuard
{
    /**
     * @var list<string>
     */
    private array $patterns = [
        '/for\s+(girls|boys|men|women)\s+only/i',
        '/(men|women|boys|girls)\s+(are|aren\'t|cannot|can\'t|should|shouldn\'t)/i',
        '/better\s+than\s+(men|women|boys|girls)/i',
    ];

    public function shouldUseInclusiveFallback(string $message): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $message) === 1) {
                return true;
            }
        }

        return false;
    }
}
