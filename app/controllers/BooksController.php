<?php
/**
 * Add Book Controller
 * ================================================
 * BOOK CRUD: CREATE OPERATION
 * Handles book insertion via POST request
 * Uses books_traitement.php functions
 */

session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/books_traitement.php';

// BOOK CRUD: Check if form was submitted via POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_book') {
    
    // BOOK CRUD: Extract POST data into array
    $data = [
        'title' => $_POST['title'] ?? '',
        'author' => $_POST['author'] ?? '',
        'genre' => $_POST['genre'] ?? '',
        'cover' => $_POST['cover'] ?? '📖',
        'coinCost' => $_POST['coinCost'] ?? 100,
        'xpReward' => $_POST['xpReward'] ?? 150,
        'coinReward' => $_POST['coinReward'] ?? 40,
        'audience' => $_POST['audience'] ?? 'All',
        'trending' => isset($_POST['trending']) ? 1 : 0,
        'description' => $_POST['description'] ?? ''
    ];

    // BOOK CRUD: Call AddBook function from books_traitement.php
    $bookId = AddBook($cnx, $data);

    if ($bookId) {
        // BOOK CRUD: Success - store message and redirect with success flag
        $_SESSION['book_success'] = "Book added successfully!";
        header("Location: ../index.php?view=admin&section=books&addbook=ok&id=" . $bookId);
        exit;
    } else {
        // BOOK CRUD: Failure - store error message and redirect
        $_SESSION['book_error'] = "Failed to add book. Please check all required fields.";
        header("Location: ../index.php?view=admin&section=books&addbook=error");
        exit;
    }
}

// BOOK CRUD: Invalid request - redirect back to admin
header("Location: ../index.php?view=admin&section=books");
exit;
?>


