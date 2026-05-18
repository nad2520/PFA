<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FilteringIntegrationTest extends TestCase
{
    private function captureJson(callable $callback): void
    {
        try {
            $callback();
        } catch (TestResponseCaptured) {
        }
    }

    protected function tearDown(): void
    {
        FilteringTestHelper::resetDatabase();
        $_GET = [];
    }

    public function testSearchControllerReturnsMatchingBooksAndCount(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();
        FilteringTestHelper::setDatabasePdo(
            FilteringTestHelper::createSqliteCatalog($fixture['books'], $fixture['book_genres'])
        );

        $_GET['q'] = 'Chronicle';

        $controller = new TestableBooksController();
        $this->captureJson(static fn () => $controller->search());

        $this->assertSame(200, $controller->lastStatus);
        $this->assertTrue($controller->lastPayload['success']);
        $this->assertSame(1, $controller->lastPayload['count']);
        $this->assertSame('Chronicle of Code', $controller->lastPayload['data'][0]['title']);
    }

    public function testCatalogBooksMapsGenresSortsIdsAndKeepsFallbackGenre(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();
        FilteringTestHelper::setDatabasePdo(
            FilteringTestHelper::createSqliteCatalog($fixture['books'], $fixture['book_genres'])
        );

        $controller = new TestableBooksController();
        $this->captureJson(static fn () => $controller->catalogBooks());

        $this->assertSame(200, $controller->lastStatus);
        $this->assertTrue($controller->lastPayload['success']);
        $this->assertSame([1, 2, 3], array_column($controller->lastPayload['data'], 'id'));
        $this->assertSame(['Adventure', 'Fantasy'], $controller->lastPayload['data'][0]['genres']);
        $this->assertSame(['Drama'], $controller->lastPayload['data'][1]['genres']);
        $this->assertTrue($controller->lastPayload['data'][0]['trending']);
        $this->assertFalse($controller->lastPayload['data'][1]['trending']);
    }

    public function testCatalogBooksReturnsAnEmptyCollectionWhenNoBooksExist(): void
    {
        FilteringTestHelper::setDatabasePdo(FilteringTestHelper::createSqliteCatalog([], []));

        $controller = new TestableBooksController();
        $this->captureJson(static fn () => $controller->catalogBooks());

        $this->assertSame(200, $controller->lastStatus);
        $this->assertSame([], $controller->lastPayload['data']);
    }
}
