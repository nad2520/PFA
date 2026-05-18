<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Repository\BookRepository;
use App\Service\BookSearchService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\BookSearchService
 */
final class BookSearchTest extends TestCase
{
    private BookRepository $repository;
    private BookSearchService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(BookRepository::class);
        $this->service = new BookSearchService($this->repository);
    }

    /**
     * TC_SEARCH_01
     * @covers \App\Service\BookSearchService::searchByTitle
     */
    public function testSearchByTitle(): void
    {
        $this->repository->method('searchByTitle')->willReturn([
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'year' => 2008],
        ]);

        $results = $this->service->searchByTitle('Clean Code');

        $this->assertCount(1, $results);
        $this->assertEquals('Clean Code', $results[0]['title']);
    }

    /**
     * TC_SEARCH_02
     * @covers \App\Service\BookSearchService::searchByAuthor
     */
    public function testSearchByAuthor(): void
    {
        $this->repository->method('searchByAuthor')->willReturn([
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'year' => 2008],
            ['title' => 'Clean Architecture', 'author' => 'Robert C. Martin', 'year' => 2017],
        ]);

        $results = $this->service->searchByAuthor('Robert C. Martin');

        $this->assertCount(2, $results);
        $this->assertContains('Clean Architecture', array_column($results, 'title'));
    }

    /**
     * TC_SEARCH_03
     * @covers \App\Service\BookSearchService::searchByYear
     */
    public function testSearchByYear(): void
    {
        $this->repository->method('searchByYear')->willReturn([
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'year' => 2008],
        ]);

        $results = $this->service->searchByYear('2008');

        $this->assertCount(1, $results);
        $this->assertEquals(2008, $results[0]['year']);
    }

    /**
     * TC_SEARCH_04
     * @covers \App\Service\BookSearchService::searchByTitle
     */
    public function testSearchByPartialTitle(): void
    {
        $this->repository->method('searchByTitle')->willReturn([
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'year' => 2008],
            ['title' => 'Clean Architecture', 'author' => 'Robert C. Martin', 'year' => 2017],
        ]);

        $results = $this->service->searchByTitle('Clean');

        $this->assertCount(2, $results);
        $this->assertContains('Clean Code', array_column($results, 'title'));
    }

    /**
     * TC_SEARCH_05
     * @covers \App\Service\BookSearchService::searchByAuthor
     */
    public function testSearchByPartialAuthor(): void
    {
        $this->repository->method('searchByAuthor')->willReturn([
            ['title' => 'Refactoring', 'author' => 'Martin Fowler', 'year' => 1999],
        ]);

        $results = $this->service->searchByAuthor('Fowler');

        $this->assertCount(1, $results);
        $this->assertEquals('Refactoring', $results[0]['title']);
    }

    /**
     * TC_SEARCH_06
     * @covers \App\Service\BookSearchService::searchByTitle
     */
    public function testSearchWithNoResults(): void
    {
        $this->repository->method('searchByTitle')->willReturn([]);

        $results = $this->service->searchByTitle('Unknown Book');

        $this->assertEmpty($results);
    }

    /**
     * TC_SEARCH_07
     * @covers \App\Service\BookSearchService::searchByTitle
     */
    public function testSearchWithEmptyInput(): void
    {
        $results = $this->service->searchByTitle('');

        $this->assertEmpty($results);
    }

    /**
     * TC_SEARCH_08
     * @covers \App\Service\BookSearchService::searchByAuthor
     */
    public function testSearchWithOnlySpaces(): void
    {
        $results = $this->service->searchByAuthor('   ');

        $this->assertEmpty($results);
    }

    /**
     * TC_SEARCH_12
     * @covers \App\Service\BookSearchService::isSearchButtonEnabled
     */
    public function testSearchButtonDisabledWhenEmpty(): void
    {
        $this->assertFalse($this->service->isSearchButtonEnabled(''));
        $this->assertFalse($this->service->isSearchButtonEnabled('   '));
        $this->assertTrue($this->service->isSearchButtonEnabled('book'));
    }

    /**
     * TC_SEARCH_13
     * @covers \App\Service\BookSearchService::resetFilters
     */
    public function testFiltersResetWhenSearchCleared(): void
    {
        $filters = $this->service->resetFilters();

        $this->assertEquals(['title' => null, 'author' => null, 'year' => null], $filters);
    }
}
