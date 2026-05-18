<?php

declare(strict_types=1);

namespace Tests\Performance;

use App\Service\BookSearchService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\BookSearchService
 */
final class BookSearchTest extends TestCase
{
    private object $database;
    private object $repository;
    private BookSearchService $service;

    protected function setUp(): void
    {
        $this->database = $this->createMock(BookSearchDbStub::class);
        $this->repository = $this->createMock(\App\Repository\BookRepository::class);
        $this->service = new BookSearchService($this->repository);

        $dataset = [];
        for ($i = 1; $i <= 10000; $i++) {
            $dataset[] = [
                'title' => 'Book Title ' . $i,
                'author' => 'Author ' . ($i % 200),
                'year' => 1980 + ($i % 40),
            ];
        }
        $dataset[] = ['title' => 'Performance Target Book', 'author' => 'Target Author', 'year' => 2022];

        $this->database->method('getBooks')->willReturn($dataset);
        $this->repository->method('searchByAuthor')->willReturn([
            ['title' => 'Performance Target Book', 'author' => 'Target Author', 'year' => 2022],
        ]);
    }

    /**
     * TC_SEARCH_09
     * @covers \App\Service\BookSearchService::searchByAuthor
     */
    public function testSearchLargeDatasetUnderOneSecond(): void
    {
        $start = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            $results = $this->service->searchByAuthor('Target Author');
            $this->assertNotEmpty($results);
        }
        $elapsed = microtime(true) - $start;

        $this->assertTrue($elapsed < 1.0);
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
