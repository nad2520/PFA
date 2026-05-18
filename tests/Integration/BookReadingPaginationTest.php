<?php

namespace Tests\Integration;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BookReadingPaginationTest extends TestCase
{
    /**
     * TC_READ_07
     *
     * @covers \Tests\Integration\BookReader::goToPage
     * @covers \Tests\Integration\BookReader::getCurrentPageContent
     */
    public function testDirectPageNavigation()
    {
        $pages = [
            1 => 'Page 1 content',
            2 => 'Page 2 content',
            3 => 'Page 3 content',
            4 => 'Page 4 content',
            5 => 'Page 5 content',
            6 => 'Page 6 content',
            7 => 'Page 7 content',
            8 => 'Page 8 content',
            9 => 'Page 9 content',
            10 => 'Page 10 content',
        ];

        $repository = new InMemoryBookPageRepository('book-1', $pages);
        $session = new InMemoryReadingSession();

        $reader = new BookReader($repository, $session);
        $reader->openBook('book-1', 'user-1');
        $reader->goToPage(5);

        $this->assertSame('Page 5 content', $reader->getCurrentPageContent());
    }

    /**
     * TC_READ_08
     *
     * @covers \Tests\Integration\BookReader::persistState
     * @covers \Tests\Integration\BookReader::restoreState
     * @covers \Tests\Integration\BookReader::getCurrentPageNumber
     */
    public function testMaintainStateRefresh()
    {
        $pages = [
            1 => 'Page 1 content',
            2 => 'Page 2 content',
            3 => 'Page 3 content',
            4 => 'Page 4 content',
            5 => 'Page 5 content',
            6 => 'Page 6 content',
            7 => 'Page 7 content',
            8 => 'Page 8 content',
            9 => 'Page 9 content',
            10 => 'Page 10 content',
        ];

        $repository = new InMemoryBookPageRepository('book-1', $pages);
        $session = new InMemoryReadingSession();

        $readerBeforeRefresh = new BookReader($repository, $session);
        $readerBeforeRefresh->openBook('book-1', 'user-1');
        $readerBeforeRefresh->goToPage(3);
        $readerBeforeRefresh->persistState();

        $readerAfterRefresh = new BookReader($repository, $session);
        $readerAfterRefresh->openBook('book-1', 'user-1');
        $readerAfterRefresh->restoreState();

        $this->assertSame(3, $readerAfterRefresh->getCurrentPageNumber());
        $this->assertSame('Page 3 content', $readerAfterRefresh->getCurrentPageContent());
    }
}

interface BookPageRepositoryInterface
{
    public function getTotalPages(string $bookId): int;

    public function getPageContent(string $bookId, int $pageNumber): string;
}

interface ReadingSessionInterface
{
    public function set(string $key, mixed $value): void;

    public function get(string $key, mixed $default = null): mixed;
}

final class InMemoryReadingSession implements ReadingSessionInterface
{
    private array $data = [];

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }
}

final class InMemoryBookPageRepository implements BookPageRepositoryInterface
{
    private string $bookId;
    private array $pagesByNumber;

    public function __construct(string $bookId, array $pagesByNumber)
    {
        $this->bookId = $bookId;
        $this->pagesByNumber = $pagesByNumber;
    }

    public function getTotalPages(string $bookId): int
    {
        if ($bookId !== $this->bookId) {
            throw new InvalidArgumentException('Unknown book');
        }

        return count($this->pagesByNumber);
    }

    public function getPageContent(string $bookId, int $pageNumber): string
    {
        if ($bookId !== $this->bookId) {
            throw new InvalidArgumentException('Unknown book');
        }

        if (!array_key_exists($pageNumber, $this->pagesByNumber)) {
            throw new InvalidArgumentException('Invalid page number');
        }

        return $this->pagesByNumber[$pageNumber];
    }
}

final class BookReader
{
    private BookPageRepositoryInterface $repository;
    private ReadingSessionInterface $session;

    private ?string $userId = null;
    private ?string $bookId = null;
    private int $currentPageNumber = 1;
    private int $totalPages = 0;

    public function __construct(BookPageRepositoryInterface $repository, ReadingSessionInterface $session)
    {
        $this->repository = $repository;
        $this->session = $session;
    }

    public function openBook(string $bookId, string $userId): void
    {
        $this->bookId = $bookId;
        $this->userId = $userId;
        $this->totalPages = $this->repository->getTotalPages($bookId);
        $this->currentPageNumber = 1;
    }

    public function goToPage(int $pageNumber): void
    {
        if ($pageNumber < 1 || $pageNumber > $this->totalPages) {
            throw new InvalidArgumentException('Invalid page number');
        }

        $this->currentPageNumber = $pageNumber;
    }

    public function persistState(): void
    {
        $this->session->set($this->getSessionKey(), $this->currentPageNumber);
    }

    public function restoreState(): void
    {
        $saved = $this->session->get($this->getSessionKey(), 1);
        $saved = is_int($saved) ? $saved : 1;

        if ($saved < 1) {
            $saved = 1;
        }

        if ($saved > $this->totalPages) {
            $saved = $this->totalPages;
        }

        $this->currentPageNumber = $saved;
    }

    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    public function getCurrentPageContent(): string
    {
        if ($this->bookId === null) {
            throw new InvalidArgumentException('No book opened');
        }

        return $this->repository->getPageContent($this->bookId, $this->currentPageNumber);
    }

    private function getSessionKey(): string
    {
        if ($this->userId === null || $this->bookId === null) {
            throw new InvalidArgumentException('Missing session scope');
        }

        return 'reading:' . $this->userId . ':' . $this->bookId . ':page';
    }
}

