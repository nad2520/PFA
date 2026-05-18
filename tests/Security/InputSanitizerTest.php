<?php
declare(strict_types=1);

namespace Tests\Security;

use App\Chatbot\Exceptions\ValidationException;
use App\Chatbot\Security\InputSanitizer;
use PHPUnit\Framework\TestCase;

final class InputSanitizerTest extends TestCase
{
    public function testSanitizeRemovesTagsControlCharactersAndCollapsesWhitespace(): void
    {
        $sanitizer = new InputSanitizer(50);
        $sanitized = $sanitizer->sanitize("<b>Hello</b>\x07   world\n\n\nSecond line");

        $this->assertSame("Hello world\nSecond line", $sanitized);
    }

    public function testValidateAcceptsBoundaryLengthAndLowValues(): void
    {
        $sanitizer = new InputSanitizer(4);
        $sanitized = $sanitizer->sanitize('0000');

        $sanitizer->validate('0000', $sanitized);
        $this->assertSame('0000', $sanitized);
    }

    public function testValidateRejectsMessagesThatBecomeEmptyAfterSanitization(): void
    {
        $sanitizer = new InputSanitizer();
        $sanitized = $sanitizer->sanitize('<script></script>');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('empty after sanitization');

        $sanitizer->validate('<script></script>', $sanitized);
    }
}
