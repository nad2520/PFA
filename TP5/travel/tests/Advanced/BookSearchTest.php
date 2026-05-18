<?php

declare(strict_types=1);

namespace Tests\Advanced;

use App\Repository\BookRepository;
use App\Service\BookSearchService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Repository\BookRepository
 * @covers \App\Service\BookSearchService
 */
final class BookSearchTest extends TestCase
{
    private object $database;
    private BookRepository $repository;
    private BookSearchService $service;

    protected function setUp(): void
    {
        $this->database = $this->createMock(BookSearchDbStub::class);
        $this->repository = new BookRepository($this->database);
        $this->service = new BookSearchService($this->repository);
    }

    /**
     * TC_SEARCH_10
     * @covers \App\Service\BookSearchService::searchByTitle
     */
    public function testSearchWithSpecialCharacters(): void
    {
        $this->database->method('getBooks')->willReturn([
            ['title' => 'Normal Book', 'author' => 'Author A', 'year' => 2010],
        ]);

        $results = $this->service->searchByTitle('!@#$%');

        $this->assertEmpty($results);
    }

    /**
     * TC_SEARCH_11
     * @covers \App\Service\BookSearchService::searchByTitle
     */
    public function testSearchWithVeryLongInput(): void
    {
        $results = $this->service->searchByTitle(str_repeat('A', 1000));

        $this->assertEmpty($results);
    }

    /**
     * TC_SEARCH_12
     * @covers \App\Service\BookSearchService::searchByYear
     */
    public function testSearchWithInvalidYearFormat(): void
    {
        $this->assertEmpty($this->service->searchByYear('abcd'));
        $this->assertEmpty($this->service->searchByYear(99999));
    }

    /**
     * TC_SEARCH_10
     * @covers \App\Service\BookSearchService::searchByTitle
     */
    public function testSearchSqlInjectionAttempt(): void
    {
        $rawTitle = "<script>alert('x')</script>";
        $safeTitle = htmlspecialchars($rawTitle, ENT_QUOTES, 'UTF-8');

        $this->database->method('getBooks')->willReturn([
            ['title' => $rawTitle, 'author' => "O'Reilly", 'year' => 2020],
        ]);

        $results = $this->service->searchByTitle('<script>');

        $this->assertCount(1, $results);
        $this->assertEquals($safeTitle, $results[0]['title']);
        $this->assertEquals('O&#039;Reilly', $results[0]['author']);
    }
}

class BookSearchDbStub
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getBooks(): array
    {
        return [];
    }
}
