<?php

declare(strict_types=1);

namespace Tests\Integration;

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
     * TC_SEARCH_09
     * @covers \App\Service\BookSearchService::search
     */
    public function testSearchReturnsMultipleResults(): void
    {
        $this->database->method('getBooks')->willReturn([
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'year' => 2008],
            ['title' => 'Code Complete', 'author' => 'Steve McConnell', 'year' => 2004],
            ['title' => 'Refactoring', 'author' => 'Martin Fowler', 'year' => 1999],
        ]);

        $results = $this->service->search(['title' => 'Code']);

        $this->assertCount(2, $results);
        $this->assertContains('Clean Code', array_column($results, 'title'));
    }

    /**
     * TC_SEARCH_09
     * @covers \App\Service\BookSearchService::search
     */
    public function testMultiFilterSearchTitleAuthorYear(): void
    {
        $this->database->method('getBooks')->willReturn([
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'year' => 2008],
            ['title' => 'Clean Architecture', 'author' => 'Robert C. Martin', 'year' => 2017],
            ['title' => 'Code Complete', 'author' => 'Steve McConnell', 'year' => 2008],
        ]);

        $results = $this->service->search([
            'title' => 'Clean',
            'author' => 'Robert',
            'year' => 2008,
        ]);

        $this->assertCount(1, $results);
        $this->assertEquals('Clean Code', $results[0]['title']);
    }

    /**
     * TC_SEARCH_13
     * @covers \App\Service\BookSearchService::search
     */
    public function testResultsListUpdatesAfterEachSearch(): void
    {
        $this->database->expects($this->exactly(2))
            ->method('getBooks')
            ->willReturnOnConsecutiveCalls(
                [
                    ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'year' => 2008],
                    ['title' => 'Code Complete', 'author' => 'Steve McConnell', 'year' => 2004],
                ],
                [
                    ['title' => 'Clean Architecture', 'author' => 'Robert C. Martin', 'year' => 2017],
                ]
            );

        $firstResults = $this->service->search(['title' => 'Code']);
        $secondResults = $this->service->search(['title' => 'Architecture']);

        $this->assertCount(2, $firstResults);
        $this->assertCount(1, $secondResults);
        $this->assertEquals('Clean Architecture', $secondResults[0]['title']);
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
