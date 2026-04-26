<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BookModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/QuestModel.php';

class AdminController extends Controller
{
    public function index(): void
    {
        session_start();

        // Optional: basic gate (keeps existing behavior permissive; tighten later if desired).
        $users = UserModel::all();
        $books = BookModel::all();
        $posts = PostModel::all();
        $quests = QuestModel::all();

        $this->render('admin/index', [
            'users' => $users,
            'books' => $books,
            'posts' => $posts,
            'quests' => $quests,
        ], null); // admin view already includes full HTML
    }
}

