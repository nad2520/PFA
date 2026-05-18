<?php

namespace Tests\Performance;

use PHPUnit\Framework\TestCase;

class LoadTest extends TestCase
{
    private BookReaderPerformanceSimulator $reader;

    protected function setUp(): void
    {
        $pages = [];
        for ($i = 1; $i <= 100; $i++) {
            $pages[$i] = 'Page ' . $i . ' content';
        }

        $this->reader = new BookReaderPerformanceSimulator($pages);
    }

    /**
     * TC_PERF_01
     */
    public function testLoadingBookPageUnder200ms(): void
    {
        $start = microtime(true);
        $this->reader->loadPage(1);
        $end = microtime(true);

        $this->assertLessThan(0.200, $end - $start, 'Book page loading exceeded 200ms');
    }

    /**
     * TC_PERF_02
     */
    public function testNextPageNavigationUnder100ms(): void
    {
        $this->reader->loadPage(1);

        $start = microtime(true);
        $this->reader->nextPage();
        $end = microtime(true);

        $this->assertLessThan(0.100, $end - $start, 'Next page navigation exceeded 100ms');
    }

    /**
     * TC_PERF_03
     */
    public function testPreviousPageNavigationUnder100ms(): void
    {
        $this->reader->loadPage(2);

        $start = microtime(true);
        $this->reader->previousPage();
        $end = microtime(true);

        $this->assertLessThan(0.100, $end - $start, 'Previous page navigation exceeded 100ms');
    }

    /**
     * TC_PERF_04
     */
    public function testDirectPageNavigationUnder150ms(): void
    {
        $start = microtime(true);
        $this->reader->goToPage(50);
        $end = microtime(true);

        $this->assertLessThan(0.150, $end - $start, 'Direct page navigation exceeded 150ms');
    }

    /**
     * TC_PERF_05
     */
    public function testLoading100PagesLoopUnder2Seconds(): void
    {
        $start = microtime(true);
        for ($i = 1; $i <= 100; $i++) {
            $this->reader->loadPage($i);
        }
        $end = microtime(true);

        $this->assertLessThan(2.000, $end - $start, 'Loading 100 pages exceeded 2 seconds');
    }
}

final class BookReaderPerformanceSimulator
{
    private array $pages;
    private int $currentPage = 1;

    public function __construct(array $pages)
    {
        $this->pages = $pages;
    }

    public function loadPage(int $pageNumber): string
    {
        $this->currentPage = $this->clampPage($pageNumber);
        return $this->pages[$this->currentPage];
    }

    public function nextPage(): string
    {
        if ($this->currentPage < count($this->pages)) {
            $this->currentPage++;
        }

        return $this->pages[$this->currentPage];
    }

    public function previousPage(): string
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }

        return $this->pages[$this->currentPage];
    }

    public function goToPage(int $pageNumber): string
    {
        $this->currentPage = $this->clampPage($pageNumber);
        return $this->pages[$this->currentPage];
    }

    private function clampPage(int $pageNumber): int
    {
        if ($pageNumber < 1) {
            return 1;
        }

        $max = count($this->pages);
        if ($pageNumber > $max) {
            return $max;
        }

        return $pageNumber;
    }
}

