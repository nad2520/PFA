<?php
declare(strict_types=1);

namespace App\Chatbot\Security;

use App\Chatbot\Exceptions\ValidationException;

final class InputSanitizer
{
    public function __construct(private readonly int $maxMessageLength = 4000)
    {
    }

    public function sanitize(string $message): string
    {
        $withoutTags = strip_tags($message);
        $withoutControls = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $withoutTags);
        $collapsed = preg_replace('/[ \t]+/u', ' ', $withoutControls ?? '');
        $normalized = preg_replace('/\R{2,}/u', "\n", $collapsed ?? '');

        return trim($normalized ?? '');
    }

    public function validate(string $originalMessage, string $sanitizedMessage): void
    {
        if (trim($originalMessage) === '') {
            throw new ValidationException('Message cannot be empty.');
        }

        if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', $originalMessage) === 1) {
            throw new ValidationException('Message contains unsupported special characters.');
        }

        if (mb_strlen($sanitizedMessage) > $this->maxMessageLength) {
            throw new ValidationException(
                sprintf('Message exceeds the maximum length of %d characters.', $this->maxMessageLength)
            );
        }

        if ($sanitizedMessage === '') {
            throw new ValidationException('Message cannot be empty after sanitization.');
        }
    }
}
