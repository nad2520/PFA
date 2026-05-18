<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class BookModelFilteringIntegrationTest extends TestCase
{
    protected function tearDown(): void
    {
        FilteringTestHelper::resetDatabase();
    }

    public function testAllReturnsBooksInDescendingIdOrderFromTheDatabase(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();
        FilteringTestHelper::setDatabasePdo(
            FilteringTestHelper::createSqliteCatalog($fixture['books'], $fixture['book_genres'])
        );

        $rows = BookModel::all();

        $this->assertSame([3, 2, 1], array_column($rows, 'id'));
        $this->assertSame(['Zeta Mystery', 'Chronicle of Code', 'Alpha Fantasy'], array_column($rows, 'title'));
    }

    public function testAllReturnsAnEmptyArrayWhenTheBooksTableDoesNotExist(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        FilteringTestHelper::setDatabasePdo($pdo);

        $this->assertSame([], BookModel::all());
    }

    public function testSearchByTitleUsesRealDatabaseMatchingAndAlphabeticalOrdering(): void
    {
        $pdo = FilteringTestHelper::createSqliteCatalog([
            [
                'id' => 1,
                'title' => 'Chronicle Zero',
                'author' => 'One',
                'publication_year' => 2020,
                'genre' => 'Fantasy',
            ],
            [
                'id' => 2,
                'title' => 'Alpha Chronicle',
                'author' => 'Two',
                'publication_year' => 2021,
                'genre' => 'Mystery',
            ],
            [
                'id' => 3,
                'title' => 'Beta Chronicle',
                'author' => 'Three',
                'publication_year' => 2022,
                'genre' => 'Drama',
            ],
        ]);
        FilteringTestHelper::setDatabasePdo($pdo);

        $rows = BookModel::searchByTitle('Chronicle');

        $this->assertSame(
            ['Alpha Chronicle', 'Beta Chronicle', 'Chronicle Zero'],
            array_column($rows, 'title')
        );
    }

    public function testSearchByTitleWithAnEmptyStringReturnsTheWholeCatalogInTitleOrder(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();
        FilteringTestHelper::setDatabasePdo(
            FilteringTestHelper::createSqliteCatalog($fixture['books'], $fixture['book_genres'])
        );

        $rows = BookModel::searchByTitle('');

        $this->assertCount(3, $rows);
        $this->assertSame(
            ['Alpha Fantasy', 'Chronicle of Code', 'Zeta Mystery'],
            array_column($rows, 'title')
        );
    }

    public function testSearchByTitleReturnsNoMatchesForInjectionShapedInput(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();
        FilteringTestHelper::setDatabasePdo(
            FilteringTestHelper::createSqliteCatalog($fixture['books'], $fixture['book_genres'])
        );

        $rows = BookModel::searchByTitle("%' OR 1=1 --");

        $this->assertSame([], $rows);
    }

    public function testGenresMapByBookIdsReturnsOnlyRequestedBooksAndSkipsBlankGenres(): void
    {
        $pdo = FilteringTestHelper::createSqliteCatalog(
            [
                ['id' => 1, 'title' => 'Alpha Fantasy', 'author' => 'F. Author', 'publication_year' => 2021, 'genre' => 'Fantasy'],
                ['id' => 2, 'title' => 'Chronicle of Code', 'author' => 'Dev Writer', 'publication_year' => 2023, 'genre' => 'Drama'],
            ],
            [
                ['book_id' => 1, 'genre_name' => 'Fantasy'],
                ['book_id' => 1, 'genre_name' => 'Adventure'],
                ['book_id' => 1, 'genre_name' => ''],
                ['book_id' => 0, 'genre_name' => 'Ignored'],
                ['book_id' => 2, 'genre_name' => 'Drama'],
            ]
        );
        FilteringTestHelper::setDatabasePdo($pdo);

        $map = BookModel::genresMapByBookIds([1, 2, 999, 0, -4]);

        $this->assertSame(
            [
                1 => ['Adventure', 'Fantasy'],
                2 => ['Drama'],
            ],
            $map
        );
    }

    public function testGenresMapByBookIdsReturnsAnEmptyMapWhenTheJoinTableIsMissing(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('CREATE TABLE books (id INTEGER PRIMARY KEY, title TEXT, author TEXT, genre TEXT)');
        $pdo->exec("INSERT INTO books (id, title, author, genre) VALUES (1, 'Alpha Fantasy', 'F. Author', 'Fantasy')");
        FilteringTestHelper::setDatabasePdo($pdo);

        $this->assertSame([], BookModel::genresMapByBookIds([1]));
    }

    public function testCombinedFilteringWorkflowCanIntersectSearchResultsWithGenreAssignments(): void
    {
        $pdo = FilteringTestHelper::createSqliteCatalog(
            [
                ['id' => 1, 'title' => 'Alpha Fantasy', 'author' => 'F. Author', 'publication_year' => 2021, 'genre' => 'Fantasy'],
                ['id' => 2, 'title' => 'Alpha Mystery', 'author' => 'M. Author', 'publication_year' => 2022, 'genre' => 'Mystery'],
                ['id' => 3, 'title' => 'Beta Mystery', 'author' => 'B. Author', 'publication_year' => 2023, 'genre' => 'Mystery'],
            ],
            [
                ['book_id' => 1, 'genre_name' => 'Fantasy'],
                ['book_id' => 2, 'genre_name' => 'Mystery'],
                ['book_id' => 3, 'genre_name' => 'Mystery'],
            ]
        );
        FilteringTestHelper::setDatabasePdo($pdo);

        $searchMatches = BookModel::searchByTitle('Alpha');
        $genreMap = BookModel::genresMapByBookIds(array_column($searchMatches, 'id'));

        $mysteryMatches = array_values(array_filter($searchMatches, static function (array $row) use ($genreMap): bool {
            $bookId = (int)$row['id'];
            return in_array('Mystery', $genreMap[$bookId] ?? [], true);
        }));

        $this->assertCount(1, $mysteryMatches);
        $this->assertSame('Alpha Mystery', $mysteryMatches[0]['title']);
    }

    public function testSearchByTitleHandlesBoundarySizedQueryStringsWithoutCrashing(): void
    {
        $fixture = FilteringTestHelper::sampleCatalogFixture();
        FilteringTestHelper::setDatabasePdo(
            FilteringTestHelper::createSqliteCatalog($fixture['books'], $fixture['book_genres'])
        );

        $rows = BookModel::searchByTitle(str_repeat('A', 512));

        $this->assertSame([], $rows);
    }
}
