<?php
/**
 * Update Book Controller
 * ================================================
 * BOOK CRUD: UPDATE OPERATION
 * Handles book modifications via POST request
 * Uses books_traitement.php functions
 */

session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/books_traitement.php';

// BOOK CRUD: Check if form was submitted via POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_book') {
    
    // BOOK CRUD: Validate that book ID exists
    if (empty($_POST['id'])) {
        $_SESSION['book_error'] = "Book ID is required for updating.";
        header("Location: ../index.php?view=admin&section=books&editbook=error");
        exit;
    }

    $bookId = (int)$_POST['id'];

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

    // BOOK CRUD: Call updateBook function from books_traitement.php
    $result = updateBook($cnx, $bookId, $data);

    if ($result) {
        // BOOK CRUD: Success - store message and redirect with success flag
        $_SESSION['book_success'] = "Book updated successfully!";
        header("Location: ../index.php?view=admin&section=books&editbook=ok&id=" . $bookId);
        exit;
    } else {
        // BOOK CRUD: Failure - store error message and redirect
        $_SESSION['book_error'] = "Failed to update book. Please verify all fields.";
        header("Location: ../index.php?view=admin&section=books&editbook=error&id=" . $bookId);
        exit;
    }
}

// BOOK CRUD: Invalid request - redirect back to admin
header("Location: ../index.php?view=admin&section=books");
exit;
?>

