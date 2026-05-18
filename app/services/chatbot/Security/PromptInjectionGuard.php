<?php
declare(strict_types=1);

namespace App\Chatbot\Security;

final class PromptInjectionGuard
{
    /**
     * @var list<string>
     */
    private array $patterns = [
        '/ignore\s+(all|any|the|previous|prior)\s+instructions/i',
        '/reveal\s+(the\s+)?(system|developer)\s+(prompt|instructions?)/i',
        '/bypass\s+(security|guardrails|filters)/i',
        '/act\s+as\s+(the\s+)?(system|developer|administrator|admin)/i',
        '/print\s+(your\s+)?(api\s*key|token|secret|password)/i',
    ];

    public function isMalicious(string $message): bool
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $message) === 1) {
                return true;
            }
        }

        return false;
    }
}
