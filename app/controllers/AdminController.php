<?php
include_once __DIR__ . '/../models/User.php';
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../models/Book.php';
include_once __DIR__ . '/../models/Post.php';


     function getAllUsers( $cnx): array
    {
        $res = $cnx->query("SELECT * FROM users ORDER BY id DESC");
        $rows = $res->fetchAll();
        $users = [];
        foreach ($rows as $row) {
            $user = new User($row['nom'], $row['email'], $row['password']);
            $user->id = $row['id'];
            $users[] = $user;
        }
        return $users;
    }

     function searchUsers( $cnx, string $name): array
    {
        $res = $cnx->query("SELECT * FROM users WHERE nom LIKE '%$name%' ORDER BY id DESC");
        $rows = $res->fetchAll();
        $users = [];
        foreach ($rows as $row) {
            $user = new User($row['nom'], $row['email'], $row['password']);
            $user->id = $row['id'];
            $users[] = $user;
        }
        return $users;
    }

     function getAllBooks( $cnx): array
    {
        $res = $cnx->query("SELECT * FROM books ORDER BY id DESC");
        $rows = $res->fetchAll();
        $books = [];
        foreach ($rows as $row) {
            $book = new Book($row['title'], $row['author'], $row['genre']);
            $book->id = $row['id'];
            $books[] = $book;
        }
        return $books;
    }

    function searchBooks( $cnx, string $name): array
    {
        
        $res = $cnx->query("SELECT * FROM books WHERE title LIKE '%$name%' OR author LIKE '%$name%' OR genre LIKE '%$name%' ORDER BY id DESC");
        $rows = $res->fetchAll();
        $books = [];
        foreach ($rows as $row) {
            $book = new Book($row['title'], $row['author'], $row['genre']);
            $book->id = $row['id'];
            $books[] = $book;
        }
        return $books;
    }

     function getAllPosts( $cnx): array
    {
        $res = $cnx->query("SELECT * FROM posts ORDER BY id DESC");
        $rows = $res->fetchAll();
        $posts = [];
        foreach ($rows as $row) {
            $post = new Post($row['title'], $row['content'], $row['tag']);
            $post->id = $row['id'];
            $posts[] = $post;
        }
        return $posts;

    }

     function searchPosts( $cnx, string $name): array
    {

        $res = $cnx->query("SELECT * FROM posts WHERE tag LIKE '%$name%' OR status LIKE '%$name%' OR content LIKE '%$name%' ORDER BY id DESC");
        $rows = $res->fetchAll();
        $posts = [];
        foreach ($rows as $row) {
            $post = new Post($row['title'], $row['content'], $row['tag']);
            $post->id = $row['id'];
            $posts[] = $post;
        }
        return $posts;
    }

    


