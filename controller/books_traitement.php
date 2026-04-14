<?php 
/**
 * Books Traitement Controller
 * ==============================================================
 * Contains all database operation functions for books
 * Uses Book model class for object creation
 * Pattern follows existing project architecture (traitement.php)
 */

include("../config/database.php");
include("../model/Book.php");

/**
 * ===== CREATE =====
 * Add a new book to the database
 * 
 * @param PDO $cnx Database connection
 * @param array $data Book data array
 * @return bool|int Book ID on success, false on failure
 */
function AddBook($cnx, $data) {
    // BOOK CRUD: Validation of required fields
    if (empty($data['title']) || empty($data['author']) || empty($data['genre'])) {
        return false;
    }

    // BOOK CRUD: Prepare book data with defaults
    $title = trim($data['title']);
    $author = trim($data['author']);
    $genre = trim($data['genre']);
    $cover = !empty($data['cover']) ? trim($data['cover']) : '📖';
    $coinCost = isset($data['coinCost']) ? (int)$data['coinCost'] : 100;
    $xpReward = isset($data['xpReward']) ? (int)$data['xpReward'] : 150;
    $coinReward = isset($data['coinReward']) ? (int)$data['coinReward'] : 40;
    $audience = !empty($data['audience']) ? $data['audience'] : 'All';
    $trending = isset($data['trending']) ? 1 : 0;
    $description = !empty($data['description']) ? trim($data['description']) : '';

    // BOOK CRUD: Build and execute INSERT query with prepared statements
    $query = "INSERT INTO books (title, author, genre, cover, coinCost, xpReward, coinReward, audience, trending, description) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $cnx->prepare($query);
    
    $result = $stmt->execute([
        $title,
        $author,
        $genre,
        $cover,
        $coinCost,
        $xpReward,
        $coinReward,
        $audience,
        $trending,
        $description
    ]);

    // BOOK CRUD: Return book ID on success, false on failure
    if ($result) {
        return $cnx->lastInsertId();
    }
    return false;
}

/**
 * ===== READ ALL =====
 * Fetch all books from database
 * Returns array of Book objects
 * 
 * @param PDO $cnx Database connection
 * @return array Array of Book objects
 */
function getAllBooks($cnx) {
    // BOOK CRUD: Query to retrieve all books ordered by creation date
    $query = "SELECT * FROM books ORDER BY created_at DESC, id DESC";
    $result = $cnx->query($query);

    $rows = $result->fetchAll();
    $books = [];

    // BOOK CRUD: Convert each row to Book object
    foreach ($rows as $row) {
        $book = new Book(
            $row['title'],
            $row['author'],
            $row['genre'],
            $row['cover'],
            $row['coinCost'],
            $row['xpReward'],
            $row['coinReward'],
            $row['audience'],
            $row['trending'],
            $row['description']
        );
        $book->id = $row['id'];
        $book->created_at = $row['created_at'];

        $books[] = $book;
    }

    return $books;
}

/**
 * ===== READ ONE =====
 * Fetch a single book by ID
 * 
 * @param PDO $cnx Database connection
 * @param int $id Book ID
 * @return Book|null Book object or null if not found
 */
function getBookById($cnx, $id) {
    // BOOK CRUD: Query to fetch specific book by primary key
    $query = "SELECT * FROM books WHERE id = ?";
    $stmt = $cnx->prepare($query);
    $stmt->execute([$id]);

    $row = $stmt->fetch();

    // BOOK CRUD: Return Book object if found, null otherwise
    if ($row) {
        $book = new Book(
            $row['title'],
            $row['author'],
            $row['genre'],
            $row['cover'],
            $row['coinCost'],
            $row['xpReward'],
            $row['coinReward'],
            $row['audience'],
            $row['trending'],
            $row['description']
        );
        $book->id = $row['id'];
        $book->created_at = $row['created_at'];

        return $book;
    }
    return null;
}

/**
 * ===== SEARCH/FILTER =====
 * Search books by title or filter by criteria
 * 
 * @param PDO $cnx Database connection
 * @param string $search Search term for title or author
 * @return array Array of Book objects
 */
function searchBooks($cnx, $search) {
    // BOOK CRUD: Query with LIKE for title or author search
    $query = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY created_at DESC";
    $stmt = $cnx->prepare($query);
    
    $searchTerm = '%' . $search . '%';
    $stmt->execute([$searchTerm, $searchTerm]);

    $rows = $stmt->fetchAll();
    $books = [];

    // BOOK CRUD: Convert rows to Book objects
    foreach ($rows as $row) {
        $book = new Book(
            $row['title'],
            $row['author'],
            $row['genre'],
            $row['cover'],
            $row['coinCost'],
            $row['xpReward'],
            $row['coinReward'],
            $row['audience'],
            $row['trending'],
            $row['description']
        );
        $book->id = $row['id'];
        $book->created_at = $row['created_at'];

        $books[] = $book;
    }

    return $books;
}

/**
 * ===== FILTER BY GENRE =====
 * Get all books of a specific genre
 * 
 * @param PDO $cnx Database connection
 * @param string $genre Genre name
 * @return array Array of Book objects
 */
function getBooksByGenre($cnx, $genre) {
    // BOOK CRUD: Query to filter books by genre
    $query = "SELECT * FROM books WHERE genre = ? ORDER BY created_at DESC";
    $stmt = $cnx->prepare($query);
    $stmt->execute([$genre]);

    $rows = $stmt->fetchAll();
    $books = [];

    foreach ($rows as $row) {
        $book = new Book(
            $row['title'],
            $row['author'],
            $row['genre'],
            $row['cover'],
            $row['coinCost'],
            $row['xpReward'],
            $row['coinReward'],
            $row['audience'],
            $row['trending'],
            $row['description']
        );
        $book->id = $row['id'];
        $book->created_at = $row['created_at'];

        $books[] = $book;
    }

    return $books;
}

/**
 * ===== FILTER BY AUDIENCE =====
 * Get books for specific audience
 * 
 * @param PDO $cnx Database connection
 * @param string $audience Audience (All, +18 Only, -18 Only)
 * @return array Array of Book objects
 */
function getBooksByAudience($cnx, $audience) {
    // BOOK CRUD: Query to filter books by audience
    $query = "SELECT * FROM books WHERE audience = ? ORDER BY created_at DESC";
    $stmt = $cnx->prepare($query);
    $stmt->execute([$audience]);

    $rows = $stmt->fetchAll();
    $books = [];

    foreach ($rows as $row) {
        $book = new Book(
            $row['title'],
            $row['author'],
            $row['genre'],
            $row['cover'],
            $row['coinCost'],
            $row['xpReward'],
            $row['coinReward'],
            $row['audience'],
            $row['trending'],
            $row['description']
        );
        $book->id = $row['id'];
        $book->created_at = $row['created_at'];

        $books[] = $book;
    }

    return $books;
}

/**
 * ===== GET TRENDING =====
 * Get all trending books
 * 
 * @param PDO $cnx Database connection
 * @return array Array of Book objects
 */
function getTrendingBooks($cnx) {
    // BOOK CRUD: Query to get trending books (trending = 1)
    $query = "SELECT * FROM books WHERE trending = 1 ORDER BY created_at DESC";
    $result = $cnx->query($query);

    $rows = $result->fetchAll();
    $books = [];

    foreach ($rows as $row) {
        $book = new Book(
            $row['title'],
            $row['author'],
            $row['genre'],
            $row['cover'],
            $row['coinCost'],
            $row['xpReward'],
            $row['coinReward'],
            $row['audience'],
            $row['trending'],
            $row['description']
        );
        $book->id = $row['id'];
        $book->created_at = $row['created_at'];

        $books[] = $book;
    }

    return $books;
}

/**
 * ===== UPDATE =====
 * Update an existing book
 * 
 * @param PDO $cnx Database connection
 * @param int $id Book ID to update
 * @param array $data Updated book data
 * @return bool True on success, false on failure
 */
function updateBook($cnx, $id, $data) {
    // BOOK CRUD: Validation of required fields
    if (empty($data['title']) || empty($data['author']) || empty($data['genre'])) {
        return false;
    }

    // BOOK CRUD: Verify book exists before updating
    $existingBook = getBookById($cnx, $id);
    if (!$existingBook) {
        return false;
    }

    // BOOK CRUD: Prepare updated book data
    $title = trim($data['title']);
    $author = trim($data['author']);
    $genre = trim($data['genre']);
    $cover = !empty($data['cover']) ? trim($data['cover']) : '📖';
    $coinCost = isset($data['coinCost']) ? (int)$data['coinCost'] : 100;
    $xpReward = isset($data['xpReward']) ? (int)$data['xpReward'] : 150;
    $coinReward = isset($data['coinReward']) ? (int)$data['coinReward'] : 40;
    $audience = !empty($data['audience']) ? $data['audience'] : 'All';
    $trending = isset($data['trending']) ? 1 : 0;
    $description = !empty($data['description']) ? trim($data['description']) : '';

    // BOOK CRUD: Build and execute UPDATE query with prepared statements
    $query = "UPDATE books SET title = ?, author = ?, genre = ?, cover = ?, coinCost = ?, 
              xpReward = ?, coinReward = ?, audience = ?, trending = ?, description = ? 
              WHERE id = ?";
    $stmt = $cnx->prepare($query);

    $result = $stmt->execute([
        $title,
        $author,
        $genre,
        $cover,
        $coinCost,
        $xpReward,
        $coinReward,
        $audience,
        $trending,
        $description,
        $id
    ]);

    return $result;
}

/**
 * ===== DELETE =====
 * Delete a book from database
 * 
 * @param PDO $cnx Database connection
 * @param int $id Book ID to delete
 * @return bool True on success, false on failure
 */
function deleteBook($cnx, $id) {
    // BOOK CRUD: Validate ID is provided
    if (empty($id) || $id <= 0) {
        return false;
    }

    // BOOK CRUD: Verify book exists before deleting
    $existingBook = getBookById($cnx, $id);
    if (!$existingBook) {
        return false;
    }

    // BOOK CRUD: Execute delete statement with prepared query
    $query = "DELETE FROM books WHERE id = ?";
    $stmt = $cnx->prepare($query);

    return $stmt->execute([$id]);
}

/**
 * ===== UTILITY =====
 * Count total books in database
 * 
 * @param PDO $cnx Database connection
 * @return int Total number of books
 */
function countBooks($cnx) {
    // BOOK CRUD: Query to count all books
    $query = "SELECT COUNT(*) as total FROM books";
    $result = $cnx->query($query);
    $row = $result->fetch();

    return $row['total'] ?? 0;
}

/**
 * ===== UTILITY =====
 * Get books as JSON array (for API endpoints)
 * 
 * @param PDO $cnx Database connection
 * @return array Books data as associative arrays
 */
function getAllBooksAsArray($cnx) {
    // BOOK CRUD: Query to get all books as raw data
    $query = "SELECT * FROM books ORDER BY created_at DESC, id DESC";
    $result = $cnx->query($query);

    return $result->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * ===== UTILITY =====
 * Get single book as array (for API endpoints)
 * 
 * @param PDO $cnx Database connection
 * @param int $id Book ID
 * @return array|null Book data as array or null
 */
function getBookByIdAsArray($cnx, $id) {
    // BOOK CRUD: Query to get single book as raw data
    $query = "SELECT * FROM books WHERE id = ?";
    $stmt = $cnx->prepare($query);
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row : null;
}

?>
