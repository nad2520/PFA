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
        (new AdminController())->index();
    });

    // Auth
    $router->post('/auth', function () {
        require APP_PATH . '/controllers/AuthController.php';
        (new AuthController())->handle();
    });
    $router->get('/logout', function () {
        require APP_PATH . '/controllers/AuthController.php';
        (new AuthController())->logout();
    });

    // Admin actions (keep simple, no JS/HTML here)
    $router->post('/admin/users/update', function () {
        require APP_PATH . '/controllers/UsersController.php';
        (new UsersController())->update();
    });
    $router->get('/admin/users/delete', function () {
        require APP_PATH . '/controllers/UsersController.php';
        (new UsersController())->delete();
    });

    $router->post('/admin/books/create', function () {
        require APP_PATH . '/controllers/BooksController.php';
        (new BooksController())->create();
    });
    $router->post('/admin/books/update', function () {
        require APP_PATH . '/controllers/BooksController.php';
        (new BooksController())->update();
    });
    $router->get('/admin/books/delete', function () {
        require APP_PATH . '/controllers/BooksController.php';
        (new BooksController())->delete();
    });

    $router->get('/admin/posts/update', function () {
        require APP_PATH . '/controllers/PostsController.php';
        (new PostsController())->update();
    });
    $router->get('/admin/posts/delete', function () {
        require APP_PATH . '/controllers/PostsController.php';
        (new PostsController())->delete();
    });
};

