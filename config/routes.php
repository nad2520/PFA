<?php
/**
 * Route definitions.
 *
 * For compatibility, we keep the existing query-string routing (`?view=...`)
 * and also add clean paths where possible.
 */

return function (Router $router): void {
    $router->get('/', function () {
        require APP_PATH . '/controllers/LandingController.php';
        (new LandingController())->index();
    });

    $router->get('/index.php', function () {
        require APP_PATH . '/controllers/LandingController.php';
        (new LandingController())->index();
    });

    $router->get('/user', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->home();
    });
    $router->get('/user/home.php', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->home();
    });
    $router->get('/user/index.php', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->home();
    });
    $router->get('/home/index.php', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->home();
    });
    $router->get('/profile', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->profile();
    });
    $router->get('/store', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->store();
    });
    $router->get('/book-detail', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->bookDetail();
    });
    $router->get('/read-book', function () {
        require APP_PATH . '/controllers/UserPageController.php';
        (new UserPageController())->readBook();
    });

    $router->get('/admin', function () {
        require APP_PATH . '/controllers/AdminController.php';
        (new AdminController())->index();
    });
    $router->get('/admin/index.php', function () {
        require APP_PATH . '/controllers/AdminController.php';
        (new AdminController())->index();
    });

    $router->post('/auth', function () {
        require APP_PATH . '/controllers/AuthController.php';
        (new AuthController())->handle();
    });
    $router->get('/logout', function () {
        require APP_PATH . '/controllers/AuthController.php';
        (new AuthController())->logout();
    });
    $router->get('/api/auth/session', function () {
        require APP_PATH . '/controllers/AuthController.php';
        (new AuthController())->sessionStatus();
    });

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

    $router->post('/admin/quests/create', function () {
        require APP_PATH . '/controllers/QuestsController.php';
        (new QuestsController())->create();
    });
    $router->post('/admin/quests/update', function () {
        require APP_PATH . '/controllers/QuestsController.php';
        (new QuestsController())->update();
    });
    $router->get('/admin/quests/delete', function () {
        require APP_PATH . '/controllers/QuestsController.php';
        (new QuestsController())->delete();
    });
    $router->post('/admin/quests/delete', function () {
        require APP_PATH . '/controllers/QuestsController.php';
        (new QuestsController())->delete();
    });
    
    $router->get('/api/books/search', function () {
        require_once APP_PATH . '/controllers/BooksController.php';
        (new BooksController())->search();
    });

    $router->get('/api/catalog/books', function () {
        require_once APP_PATH . '/controllers/BooksController.php';
        (new BooksController())->catalogBooks();
    });

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
    $router->get('/api/user/quests', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->getQuests();
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
    $router->get('/api/leaderboard/search', function () {
        require_once APP_PATH . '/controllers/UserApiController.php';
        (new UserApiController())->searchLeaderboard();
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


