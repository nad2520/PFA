<?php
/**
 * Get Books Controller
 * ================================================
 * BOOK CRUD: READ OPERATION
 * Retrieves books from database with filtering
 * Supports JSON responses for AJAX requests
 * Uses books_traitement.php functions
 */

session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/books_traitement.php';

// BOOK CRUD: Define allowed parameters to prevent injection
$action = isset($_GET['action']) ? $_GET['action'] : 'getall';
$format = isset($_GET['format']) ? $_GET['format'] : 'json'; // json or html

// BOOK CRUD: Set JSON response header if format is JSON
if ($format === 'json') {
    header('Content-Type: application/json');
}

try {
    switch ($action) {
        // BOOK CRUD: Get all books
        case 'getall':
            // BOOK CRUD: Call getAllBooksAsArray function from books_traitement.php
            $books = getAllBooksAsArray($cnx);
            $totalCount = countBooks($cnx);
            
            if ($format === 'json') {
                echo json_encode([
                    'success' => true,
                    'data' => $books,
                    'total' => $totalCount,
                    'count' => count($books)
                ]);
            }
            break;

        // BOOK CRUD: Get single book by ID
        case 'getbyid':
            if (!isset($_GET['id'])) {
                throw new Exception('Book ID parameter is required');
            }
            
            $bookId = (int)$_GET['id'];
            
            // BOOK CRUD: Call getBookByIdAsArray function from books_traitement.php
            $book = getBookByIdAsArray($cnx, $bookId);
            
            if (!$book) {
                throw new Exception('Book not found');
            }
            
            if ($format === 'json') {
                echo json_encode([
                    'success' => true,
                    'data' => $book
                ]);
            }
            break;

        // BOOK CRUD: Get books filtered by genre
        case 'genre':
            if (!isset($_GET['value'])) {
                throw new Exception('Genre parameter is required');
            }
            
            $genre = $_GET['value'];
            
            // BOOK CRUD: Call getBooksByGenre function from books_traitement.php
            $booksObjs = getBooksByGenre($cnx, $genre);
            $books = [];
            foreach ($booksObjs as $b) {
                $books[] = [
                    'id' => $b->id,
                    'title' => $b->title,
                    'author' => $b->author,
                    'genre' => $b->genre,
                    'cover' => $b->cover,
                    'coinCost' => $b->coinCost,
                    'xpReward' => $b->xpReward,
                    'coinReward' => $b->coinReward,
                    'audience' => $b->audience,
                    'trending' => $b->trending,
                    'description' => $b->description,
                    'created_at' => $b->created_at
                ];
            }
            
            if ($format === 'json') {
                echo json_encode([
                    'success' => true,
                    'data' => $books,
                    'count' => count($books),
                    'filter' => ['type' => 'genre', 'value' => $genre]
                ]);
            }
            break;

        // BOOK CRUD: Get books filtered by audience
        case 'audience':
            if (!isset($_GET['value'])) {
                throw new Exception('Audience parameter is required');
            }
            
            $audience = $_GET['value'];
            
            // BOOK CRUD: Call getBooksByAudience function from books_traitement.php
            $booksObjs = getBooksByAudience($cnx, $audience);
            $books = [];
            foreach ($booksObjs as $b) {
                $books[] = [
                    'id' => $b->id,
                    'title' => $b->title,
                    'author' => $b->author,
                    'genre' => $b->genre,
                    'cover' => $b->cover,
                    'coinCost' => $b->coinCost,
                    'xpReward' => $b->xpReward,
                    'coinReward' => $b->coinReward,
                    'audience' => $b->audience,
                    'trending' => $b->trending,
                    'description' => $b->description,
                    'created_at' => $b->created_at
                ];
            }
            
            if ($format === 'json') {
                echo json_encode([
                    'success' => true,
                    'data' => $books,
                    'count' => count($books),
                    'filter' => ['type' => 'audience', 'value' => $audience]
                ]);
            }
            break;

        // BOOK CRUD: Get trending books
        case 'trending':
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            
            // BOOK CRUD: Call getTrendingBooks function from books_traitement.php
            $booksObjs = getTrendingBooks($cnx);
            $books = [];
            foreach ($booksObjs as $b) {
                $books[] = [
                    'id' => $b->id,
                    'title' => $b->title,
                    'author' => $b->author,
                    'genre' => $b->genre,
                    'cover' => $b->cover,
                    'coinCost' => $b->coinCost,
                    'xpReward' => $b->xpReward,
                    'coinReward' => $b->coinReward,
                    'audience' => $b->audience,
                    'trending' => $b->trending,
                    'description' => $b->description,
                    'created_at' => $b->created_at
                ];
            }
            
            // Apply limit if specified
            $books = array_slice($books, 0, $limit);
            
            if ($format === 'json') {
                echo json_encode([
                    'success' => true,
                    'data' => $books,
                    'count' => count($books)
                ]);
            }
            break;

        // BOOK CRUD: Search books by title or author
        case 'search':
            if (!isset($_GET['q'])) {
                throw new Exception('Search query parameter is required');
            }
            
            $searchTerm = $_GET['q'];
            
            // BOOK CRUD: Call searchBooks function from books_traitement.php
            $booksObjs = searchBooks($cnx, $searchTerm);
            $books = [];
            foreach ($booksObjs as $b) {
                $books[] = [
                    'id' => $b->id,
                    'title' => $b->title,
                    'author' => $b->author,
                    'genre' => $b->genre,
                    'cover' => $b->cover,
                    'coinCost' => $b->coinCost,
                    'xpReward' => $b->xpReward,
                    'coinReward' => $b->coinReward,
                    'audience' => $b->audience,
                    'trending' => $b->trending,
                    'description' => $b->description,
                    'created_at' => $b->created_at
                ];
            }
            
            if ($format === 'json') {
                echo json_encode([
                    'success' => true,
                    'data' => $books,
                    'count' => count($books),
                    'search' => $searchTerm
                ]);
            }
            break;

        // BOOK CRUD: Get book count (for statistics)
        case 'count':
            // BOOK CRUD: Call countBooks function from books_traitement.php
            $totalCount = countBooks($cnx);
            
            if ($format === 'json') {
                echo json_encode([
                    'success' => true,
                    'total' => $totalCount
                ]);
            }
            break;

        // BOOK CRUD: Invalid action
        default:
            throw new Exception('Invalid action: ' . $action);
            break;
    }

} catch (Exception $e) {
    // BOOK CRUD: Return error response in appropriate format
    if ($format === 'json') {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    } else {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }
    exit;
}
?>
