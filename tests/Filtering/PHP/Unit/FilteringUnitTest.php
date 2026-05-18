<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FilteringUnitTest extends TestCase
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

    public function testSearchByTitleWrapsTheQueryInWildcards(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with(['%Chronicle%'])
            ->willReturn(true);
        $statement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['id' => 2, 'title' => 'Chronicle of Code'],
            ]);

        $pdo = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepare'])
            ->getMock();
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM books WHERE title LIKE ? ORDER BY title ASC')
            ->willReturn($statement);

        FilteringTestHelper::setDatabasePdo($pdo);

        $results = BookModel::searchByTitle('Chronicle');

        $this->assertSame('Chronicle of Code', $results[0]['title']);
    }

    public function testSearchByTitleReturnsEmptyArrayWhenTheDatabaseThrows(): void
    {
        $pdo = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepare'])
            ->getMock();
        $pdo->expects($this->once())
            ->method('prepare')
            ->willThrowException(new PDOException('boom'));

        FilteringTestHelper::setDatabasePdo($pdo);

        $this->assertSame([], BookModel::searchByTitle('anything'));
    }

    public function testGenresMapByBookIdsIgnoresInvalidIdentifiersBeforeQuerying(): void
    {
        $this->assertSame([], BookModel::genresMapByBookIds([0, -10, 'abc']));
    }

    public function testSearchReturnsBadRequestForBlankOrMalformedQueries(): void
    {
        $controller = new TestableBooksController();

        $_GET['q'] = '   ';
        $this->captureJson(static fn () => $controller->search());
        $this->assertSame(400, $controller->lastStatus);
        $this->assertSame('Query is required', $controller->lastPayload['message']);

        $_GET['q'] = ['bad'];
        $this->captureJson(static fn () => $controller->search());
        $this->assertSame(400, $controller->lastStatus);
        $this->assertSame('Query is required', $controller->lastPayload['message']);
    }
}
