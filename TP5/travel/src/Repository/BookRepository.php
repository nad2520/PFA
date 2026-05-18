<?php

declare(strict_types=1);

namespace App\Repository;

class BookRepository
{
    private object $db;

    public function __construct(object $db)
    {
        $this->db = $db;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchByTitle(string $title): array
    {
        return $this->filterByTextField('title', $title);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchByAuthor(string $author): array
    {
        return $this->filterByTextField('author', $author);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchByYear(int $year): array
    {
        return array_values(array_filter(
            $this->allBooks(),
            static fn (array $book): bool => (int) ($book['year'] ?? 0) === $year
        ));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchAll(?string $title, ?string $author, ?int $year): array
    {
        $titleNeedle = $title !== null ? mb_strtolower(trim($title)) : null;
        $authorNeedle = $author !== null ? mb_strtolower(trim($author)) : null;

        return array_values(array_filter(
            $this->allBooks(),
            static function (array $book) use ($titleNeedle, $authorNeedle, $year): bool {
                if ($titleNeedle !== null) {
                    $bookTitle = mb_strtolower((string) ($book['title'] ?? ''));
                    if (!str_contains($bookTitle, $titleNeedle)) {
                        return false;
                    }
                }

                if ($authorNeedle !== null) {
                    $bookAuthor = mb_strtolower((string) ($book['author'] ?? ''));
                    if (!str_contains($bookAuthor, $authorNeedle)) {
                        return false;
                    }
                }

                if ($year !== null && (int) ($book['year'] ?? 0) !== $year) {
                    return false;
                }

                return true;
            }
        ));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function filterByTextField(string $field, string $needle): array
    {
        $term = mb_strtolower(trim($needle));
        if ($term === '') {
            return [];
        }

        return array_values(array_filter(
            $this->allBooks(),
            static function (array $book) use ($field, $term): bool {
                $value = mb_strtolower((string) ($book[$field] ?? ''));
                return str_contains($value, $term);
            }
        ));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function allBooks(): array
    {
        if (method_exists($this->db, 'getBooks')) {
            return (array) $this->db->getBooks();
        }

        if (method_exists($this->db, 'fetchAllBooks')) {
            return (array) $this->db->fetchAllBooks();
        }

        return [];
    }
}
