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
    
    $router->get('/api/books/search', function () {
        require_once APP_PATH . '/controllers/BooksController.php';
        (new BooksController())->search();
    });

    $router->get('/api/catalog/books', function () {
        require_once APP_PATH . '/controllers/BooksController.php';
        (new BooksController())->catalogBooks();
    });

    // User JSON API (session auth)
    $router->get('/api/user/profile', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->profile();
    });
    $router->post('/api/user/reading-session', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->logSession();
    });
    $router->post('/api/user/book/purchase', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->purchaseBook();
    });
    $router->post('/api/user/book/progress', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->saveProgress();
    });
    $router->post('/api/user/book/list/add', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->addBookToList();
    });
    $router->post('/api/user/book/list/remove', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->removeBookFromList();
    });
    $router->get('/api/user/back-to-lecture', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->backToLecture();
    });
    $router->post('/api/user/book/complete', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->completeBook();
    });
    $router->post('/api/user/quest/complete', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->completeQuest();
    });
    $router->post('/api/user/book/rating', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->updateRating();
    });
    $router->get('/api/leaderboard', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->leaderboard();
    });
    $router->get('/api/leaderboard/me', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->myLeaderboard();
    });
    $router->get('/api/user/preferences/categories', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->getCategoryPreferences();
    });
    $router->post('/api/user/preferences/categories', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->saveCategoryPreferences();
    });
    $router->get('/api/user/recommendations/for-you', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->forYouRecommendations();
    });
};


