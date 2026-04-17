<?php
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../models/Book.php';
include_once __DIR__ . '/../models/Post.php';


    function AddBook($cnx,$data): bool
    {
        $req = "INSERT INTO books (title, author, genre, cover, coinCost, xpReward, coinReward, audience, trending) VALUES ('".$data['title']."', '".$data['author']."', '".$data['genre']."', '".$data['cover']."', '".$data['coinCost']."', '".$data['xpReward']."', '".$data['coinReward']."', '".$data['audience']."', '".$data['trending']."')";
        $res=$cnx->query($req);
        if($res) {
            echo "<script>console.log('Book added successfully');</script>";
            return true;
        } else {
            return false;
        }
    }
    function UpdateBook($cnx,$data): void
    {
        if(isset($_POST['idb'])) {
            $book = new Book($_POST['title'], $_POST['author'], $_POST['genre']);
            $book->id = $_POST['idb'];
            $reqOld = "SELECT cover FROM books WHERE id = '" . $book->id . "'";
            $resOld = $cnx->query($reqOld);
            $OldData = $resOld->fetch();
            if($book->cover !== $OldData['cover']) {
                $cover = "'".$book->cover."'";
            } else {
                $cover = $OldData['cover'];
            }
            $req = "UPDATE books SET title='".$book->title."', author='".$book->author."', genre='".$book->genre."', cover='".$cover."', coinCost='".$book->coinCost."', xpReward='".$book->xpReward."', coinReward='".$book->coinReward."', audience='".$book->audience."', trending='".$book->trending."' WHERE id='".$book->id."' where cover is not null";
            $res=$cnx->query($req);
            if($res) {
                header("Location: index.php?view=admin&editbook=ok");
            } else {
                echo "Error updating book";
            }
        }
    }
    function DeleteBook($cnx,$id): void
    {
        if(isset($_GET['idb'])) {
            $id_book = $_GET['idb'];
            $req = "DELETE FROM books WHERE id = '" . $id_book . "'";
            $res=$cnx->query($req);
            if($res) {
                header("Location: index.php?view=admin&deletebook=ok");
            } else {
                echo "Error deleting book";
            }
        }
    } 
    function SearchBook($cnx,$data): array
    {
        $req = "SELECT * FROM books WHERE title LIKE '%".$data['title']."%' OR author LIKE '%".$data['author']."%' OR genre LIKE '%".$data['genre']."%'";
        $res=$cnx->query($req);
        $rows=$res->fetchAll();
        $books=[];
        foreach($rows as $row) {
            $book = new Book($row['title'], $row['author'], $row['genre'], $row['cover'], $row['coinCost'], $row['xpReward'], $row['coinReward'], $row['audience'], $row['trending']);
            $book->id = $row['id'];
            $books[] = $book;
        }
        return $books;
    }

    


