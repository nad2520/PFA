<?php
declare(strict_types=1);

final class TestableBooksController extends BooksController
{
    /** @var array<string, mixed> */
    public array $lastPayload = [];
    public int $lastStatus = 200;

    protected function json(array $payload, int $status = 200): void
    {
        $this->lastPayload = $payload;
        $this->lastStatus = $status;
        throw new TestResponseCaptured();
    }
}

final class TestResponseCaptured extends RuntimeException
{
}
