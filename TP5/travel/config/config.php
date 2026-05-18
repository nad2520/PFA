<?php

return [

    // =========================
    // 🗄️ Configuration DB
    // =========================
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'dbname' => 'tp_testing',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],

    // =========================
    // 🧪 Mode Mock (IMPORTANT)
    // =========================
    'use_fake_db' => true,

    // =========================
    // 🔐 Session
    // =========================
    'session_name' => 'TP_SESSION',

    // =========================
    // ⚙️ App
    // =========================
    'app' => [
        'env' => 'dev', // dev | prod
        'debug' => true
    ]

];