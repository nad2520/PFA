<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\BookRepository;

class BookSearchService
{
    private BookRepository $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchByTitle(string $title): array
    {
        $normalized = $this->normalizeTextInput($title);
        if ($normalized === null) {
            return [];
        }

        return $this->sanitizeResults($this->repository->searchByTitle($normalized));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchByAuthor(string $author): array
    {
        $normalized = $this->normalizeTextInput($author);
        if ($normalized === null) {
            return [];
        }

        return $this->sanitizeResults($this->repository->searchByAuthor($normalized));
    }

    /**
     * @param mixed $year
     * @return array<int, array<string, mixed>>
     */
    public function searchByYear($year): array
    {
        $normalizedYear = $this->normalizeYearInput($year);
        if ($normalizedYear === null) {
            return [];
        }

        return $this->sanitizeResults($this->repository->searchByYear($normalizedYear));
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filters): array
    {
        $title = array_key_exists('title', $filters)
            ? $this->normalizeTextInput((string) $filters['title'])
            : null;
        $author = array_key_exists('author', $filters)
            ? $this->normalizeTextInput((string) $filters['author'])
            : null;
        $year = array_key_exists('year', $filters)
            ? $this->normalizeYearInput($filters['year'])
            : null;

        if ($title === null && $author === null && $year === null) {
            return [];
        }

        return $this->sanitizeResults($this->repository->searchAll($title, $author, $year));
    }

    public function isSearchButtonEnabled(string $input): bool
    {
        return trim($input) !== '';
    }

    /**
     * @return array<string, null>
     */
    public function resetFilters(): array
    {
        return [
            'title' => null,
            'author' => null,
            'year' => null,
        ];
    }

    private function normalizeTextInput(string $input): ?string
    {
        $value = trim($input);
        if ($value === '' || strlen($value) > 255) {
            return null;
        }

        return $value;
    }

    /**
     * @param mixed $year
     */
    private function normalizeYearInput($year): ?int
    {
        if (is_int($year)) {
            $normalized = $year;
        } elseif (is_string($year)) {
            $trimmed = trim($year);
            if ($trimmed === '' || !ctype_digit($trimmed)) {
                return null;
            }
            $normalized = (int) $trimmed;
        } else {
            return null;
        }

        if ($normalized < 1000 || $normalized > 9999) {
            return null;
        }

        return $normalized;
    }

    /**
     * @param array<int, array<string, mixed>> $results
     * @return array<int, array<string, mixed>>
     */
    private function sanitizeResults(array $results): array
    {
        foreach ($results as &$book) {
            if (isset($book['title'])) {
                $book['title'] = htmlspecialchars((string) $book['title'], ENT_QUOTES, 'UTF-8');
            }
            if (isset($book['author'])) {
                $book['author'] = htmlspecialchars((string) $book['author'], ENT_QUOTES, 'UTF-8');
            }
        }

        return $results;
    }
}
