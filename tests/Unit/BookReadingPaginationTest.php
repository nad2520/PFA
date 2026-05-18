<?php

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BookReadingPaginationTest extends TestCase
{
    /**
     * TC_READ_01
     *
     * @covers \Tests\Unit\BookReader::openBook
     * @covers \Tests\Unit\BookReader::getCurrentPageContent
     */
    public function testDisplayFirstPage()
    {
        $repository = $this->createMock(BookPageRepositoryInterface::class);
        $repository->method('getTotalPages')->with('book-1')->willReturn(10);
        $repository->method('getPageContent')->with('book-1', 1)->willReturn('Page 1 content');

        $reader = new BookReader($repository);
        $reader->openBook('book-1');

        $this->assertSame('Page 1 content', $reader->getCurrentPageContent());
    }

    /**
     * TC_READ_02
     *
     * @covers \Tests\Unit\BookReader::nextPage
     * @covers \Tests\Unit\BookReader::getCurrentPageContent
     */
    public function testNavigateToNextPage()
    {
        $repository = $this->createMock(BookPageRepositoryInterface::class);
        $repository->method('getTotalPages')->with('book-1')->willReturn(10);
        $repository
            ->method('getPageContent')
            ->willReturnCallback(function (string $bookId, int $pageNumber) {
                $this->assertSame('book-1', $bookId);
                $this->assertTrue($pageNumber === 1 || $pageNumber === 2);
                return $pageNumber === 1 ? 'Page 1 content' : 'Page 2 content';
            });

        $reader = new BookReader($repository);
        $reader->openBook('book-1');
        $reader->nextPage();

        $this->assertSame('Page 2 content', $reader->getCurrentPageContent());
    }

    /**
     * TC_READ_03
     *
     * @covers \Tests\Unit\BookReader::previousPage
     * @covers \Tests\Unit\BookReader::getCurrentPageNumber
     */
    public function testNavigateToPrevPage()
    {
        $repository = $this->createMock(BookPageRepositoryInterface::class);
        $repository->method('getTotalPages')->with('book-1')->willReturn(10);
        $repository->method('getPageContent')->willReturn('content');

        $reader = new BookReader($repository);
        $reader->openBook('book-1');
        $reader->nextPage(); // page 2
        $reader->previousPage();

        $this->assertSame(1, $reader->getCurrentPageNumber());
    }

    /**
     * TC_READ_04
     *
     * @covers \Tests\Unit\BookReader::hasPreviousPage
     */
    public function testPrevDisabledOnFirst()
    {
        $repository = $this->createMock(BookPageRepositoryInterface::class);
        $repository->method('getTotalPages')->with('book-1')->willReturn(10);
        $repository->method('getPageContent')->willReturn('Page 1 content');

        $reader = new BookReader($repository);
        $reader->openBook('book-1');

        $this->assertFalse($reader->hasPreviousPage());
    }

    /**
     * TC_READ_05
     *
     * @covers \Tests\Unit\BookReader::hasNextPage
     * @covers \Tests\Unit\BookReader::nextPage
     */
    public function testNextDisabledOnLast()
    {
        $repository = $this->createMock(BookPageRepositoryInterface::class);
        $repository->method('getTotalPages')->with('book-1')->willReturn(10);
        $repository->method('getPageContent')->willReturn('content');

        $reader = new BookReader($repository);
        $reader->openBook('book-1');

        for ($i = 0; $i < 9; $i++) {
            $reader->nextPage();
        }

        $this->assertSame(10, $reader->getCurrentPageNumber());
        $this->assertFalse($reader->hasNextPage());
    }

    /**
     * TC_READ_06
     *
     * @covers \Tests\Unit\BookReader::getCurrentPage
     * @covers \Tests\Unit\BookReader::nextPage
     */
    public function testPageNumberDisplay()
    {
        $repository = $this->createMock(BookPageRepositoryInterface::class);
        $repository->method('getTotalPages')->with('book-1')->willReturn(10);
        $repository->method('getPageContent')->willReturn('content');

        $reader = new BookReader($repository);
        $reader->openBook('book-1');
        $reader->nextPage();

        $this->assertSame('Page 2 of 10', $reader->getCurrentPage());
    }

    /**
     * TC_READ_09
     *
     * @covers \Tests\Unit\BookReader::goToPage
     */
    public function testBoundaryNavigation()
    {
        $repository = $this->createMock(BookPageRepositoryInterface::class);
        $repository->method('getTotalPages')->with('book-1')->willReturn(10);
        $repository->method('getPageContent')->willReturn('content');

        $reader = new BookReader($repository);
        $reader->openBook('book-1');

        try {
            $reader->goToPage(0);
            $this->fail('Expected InvalidArgumentException for page 0');
        } catch (InvalidArgumentException $exception) {
            $this->assertNotNull($exception);
        }

        try {
            $reader->goToPage(11);
            $this->fail('Expected InvalidArgumentException for page above total');
        } catch (InvalidArgumentException $exception) {
            $this->assertNotNull($exception);
        }
    }
}

interface BookPageRepositoryInterface
{
    public function getTotalPages(string $bookId): int;

    public function getPageContent(string $bookId, int $pageNumber): string;
}

final class BookReader
{
    private BookPageRepositoryInterface $repository;
    private ?string $bookId = null;
    private int $currentPageNumber = 1;
    private int $totalPages = 0;

    public function __construct(BookPageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function openBook(string $bookId): void
    {
        $this->bookId = $bookId;
        $this->totalPages = $this->repository->getTotalPages($bookId);
        $this->currentPageNumber = 1;
    }

    public function nextPage(): void
    {
        if ($this->hasNextPage()) {
            $this->currentPageNumber++;
        }
    }

    public function previousPage(): void
    {
        if ($this->hasPreviousPage()) {
            $this->currentPageNumber--;
        }
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPageNumber > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->totalPages > 0 && $this->currentPageNumber < $this->totalPages;
    }

    public function getCurrentPage(): string
    {
        return 'Page ' . $this->currentPageNumber . ' of ' . $this->totalPages;
    }

    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    public function goToPage(int $pageNumber): void
    {
        if ($pageNumber < 1 || $pageNumber > $this->totalPages) {
            throw new InvalidArgumentException('Invalid page number');
        }

        $this->currentPageNumber = $pageNumber;
    }

    public function getCurrentPageContent(): string
    {
        if ($this->bookId === null) {
            throw new InvalidArgumentException('No book opened');
        }

        return $this->repository->getPageContent($this->bookId, $this->currentPageNumber);
    }
}

