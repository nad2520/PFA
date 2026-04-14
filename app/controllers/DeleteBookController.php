<?php
/**
 * Delete Book Controller
 * ================================================
 * BOOK CRUD: DELETE OPERATION
 * Handles book deletion via GET request
 * Uses books_traitement.php functions
 */

session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/books_traitement.php';

// BOOK CRUD: Check if delete request is sent via GET or POST
if (isset($_GET['id']) || isset($_POST['id'])) {
    
    // BOOK CRUD: Extract book ID from GET or POST parameter
    $bookId = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

    // BOOK CRUD: Validate that book ID exists and is valid
    if (empty($bookId) || $bookId <= 0) {
        $_SESSION['book_error'] = "Invalid book ID.";
        header("Location: ../index.php?view=admin&section=books&deletebook=error");
        exit;
    }

    // BOOK CRUD: Get book info before deleting (for success message)
    $book = getBookById($cnx, $bookId);
    
    if (!$book) {
        $_SESSION['book_error'] = "Book not found.";
        header("Location: ../index.php?view=admin&section=books&deletebook=error");
        exit;
    }

    $bookTitle = $book->title;

    // BOOK CRUD: Call deleteBook function from books_traitement.php
    $result = deleteBook($cnx, $bookId);

    if ($result) {
        // BOOK CRUD: Success - store message and redirect with success flag
        $_SESSION['book_success'] = "Book '$bookTitle' deleted successfully!";
        header("Location: ../index.php?view=admin&section=books&deletebook=ok");
        exit;
    } else {
        // BOOK CRUD: Failure in traitement function
        $_SESSION['book_error'] = "Failed to delete book. Please try again.";
        header("Location: ../index.php?view=admin&section=books&deletebook=error");
        exit;
    }
}

// BOOK CRUD: Request method validation - must be GET request or POST with proper data
if ($_SERVER['REQUEST_METHOD'] != 'POST' && !isset($_GET['id'])) {
    header("Location: ../index.php?view=admin&section=books");
    exit;
}

// BOOK CRUD: Invalid request - redirect back to admin
header("Location: ../index.php?view=admin&section=books");
exit;
?>
