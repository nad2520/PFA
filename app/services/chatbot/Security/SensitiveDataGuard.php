<?php
declare(strict_types=1);

namespace App\Chatbot\Security;

final class SensitiveDataGuard
{
    /**
     * @var list<string>
     */
    private array $patterns = [
        '/sk-[A-Za-z0-9_-]{8,}/',
        '/\b(?:api[_ -]?key|secret|token|password)\b\s*[:=]\s*[^\s,;]+/i',
        '/\b\d{4}[- ]?\d{4}[- ]?\d{4}[- ]?\d{4}\b/',
    ];

    public function redact(string $message): string
    {
        $redacted = $message;
        foreach ($this->patterns as $pattern) {
            $redacted = (string) preg_replace($pattern, '[redacted]', $redacted);
        }

        return $redacted;
    }
}
