<?php
/**
 * Route definitions.
 *
 * For compatibility, we keep the existing query-string routing (`?view=...`)
 * and also add clean paths where possible.
 */

return function (Router $router): void {
    // Landing (default)
    $router->get('/', function () {
        require APP_PATH . '/views/home/index.php';
    });

    // Query-string compatible entry
    $router->get('/index.php', function () {
        require APP_PATH . '/views/home/index.php';
    });

    // Simple path aliases (optional)
    $router->get('/admin', function () {
        require APP_PATH . '/controllers/AdminController.php';
        require CONFIG_PATH . '/database.php';

        $usersSearch = trim((string)($_GET['users_search'] ?? ''));
        $booksSearch = trim((string)($_GET['books_search'] ?? ''));
        $postsSearch = trim((string)($_GET['posts_search'] ?? ''));

        $users = $usersSearch !== '' ? searchUsers($cnx, $usersSearch) : getAllUsers($cnx);
        $books = $booksSearch !== '' ? searchBooks($cnx, $booksSearch) : getAllBooks($cnx);
        $posts = $postsSearch !== '' ? searchPosts($cnx, $postsSearch) : getAllPosts($cnx);

        require APP_PATH . '/views/admin/legacy.php';
    });

    // Auth
    $router->post('/auth', function () {
        require APP_PATH . '/controllers/AuthController.php';
        handleAuth();
    });
    $router->get('/logout', function () {
        require APP_PATH . '/controllers/AuthController.php';
        logout();
    });

    // Admin actions (keep simple, no JS/HTML here)
    $router->post('/admin/users/update', function () {
        require APP_PATH . '/controllers/UsersController.php';
        update();
    });
    $router->get('/admin/users/delete', function () {
        require APP_PATH . '/controllers/UsersController.php';
        delete();
    });

    $router->post('/admin/books/create', function () {
        require APP_PATH . '/controllers/BooksController.php';
        require CONFIG_PATH . '/database.php';
        AddBook($cnx, $_POST);
        header("Location: /lexora_mlk/index.php?view=admin&addbook=ok");
    });
    $router->post('/admin/books/update', function () {
        require APP_PATH . '/controllers/BooksController.php';
        require CONFIG_PATH . '/database.php';
        UpdateBook($cnx, $_POST);
    });
    $router->get('/admin/books/delete', function () {
        require APP_PATH . '/controllers/BooksController.php';
        require CONFIG_PATH . '/database.php';
        DeleteBook($cnx, $_GET['idb'] ?? null);
    });

    $router->get('/admin/posts/update', function () {
        require APP_PATH . '/controllers/PostsController.php';
        require CONFIG_PATH . '/database.php';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $action = $_GET['action'] ?? '';
        if ($id > 0 && $action === 'review') {
            $cnx->query("UPDATE posts SET status = 'Reviewed' WHERE id = " . $id);
            header("Location: /lexora_mlk/index.php?view=admin&post=ok");
            return;
        }
        if ($id > 0 && $action === 'tag') {
            $cnx->query("UPDATE posts SET status = 'Pending Admin Review' WHERE id = " . $id);
            header("Location: /lexora_mlk/index.php?view=admin&post=ok");
            return;
        }
        header("Location: /lexora_mlk/index.php?view=admin&post=error");
    });
    $router->get('/admin/posts/delete', function () {
        require APP_PATH . '/controllers/PostsController.php';
        if (isset($_GET['id']) && !isset($_GET['idp'])) {
            $_GET['idp'] = $_GET['id'];
        }
        require CONFIG_PATH . '/database.php';
        DeletePost($cnx, $_GET['idp'] ?? null);
    });
};

