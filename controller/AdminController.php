<?php

require_once __DIR__ . '/Controller.php';

class AdminController extends Controller
{
    public function index(): void
    {
        $this->render('admin');
    }
}
