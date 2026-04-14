<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../model/UserModel.php';

class UserController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $this->render('user_index');
    }

    public function profile(): void
    {
        $this->render('profile');
    }

    public function store(): void
    {
        $this->render('store');
    }

    public function bookDetail(): void
    {
        $this->render('book_detail');
    }

    public function readBook(): void
    {
        $this->render('read_book');
    }
}
