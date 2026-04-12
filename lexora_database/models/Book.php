<?php
class Book extends Model
{
    public function getAll(): array
    {
        return $this->fetchAll('SELECT * FROM book ORDER BY isTrending DESC, title ASC');
    }

    public function getTrending(): array
    {
        return $this->fetchAll('SELECT * FROM book WHERE isTrending = 1 ORDER BY title ASC');
    }

    public function search(string $term): array
    {
        $term = '%' . trim($term) . '%';
        return $this->fetchAll('SELECT * FROM book WHERE title LIKE :term OR description LIKE :term OR isbn LIKE :term ORDER BY title ASC', [
            ':term' => $term,
        ]);
    }

    public function getById(string $id): ?array
    {
        return $this->fetchOne('SELECT * FROM book WHERE id = :id', [':id' => $id]);
    }

    public function getByGenre(string $genreName): array
    {
        return $this->fetchAll(
            'SELECT b.* FROM book b
             JOIN bookgenre bg ON bg.bookId = b.id
             JOIN genre g ON g.id = bg.genreId
             WHERE g.name = :genre
             ORDER BY b.title ASC',
            [':genre' => $genreName]
        );
    }
}
