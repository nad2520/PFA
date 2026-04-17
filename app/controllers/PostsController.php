<?php
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../models/Post.php';
include_once __DIR__ . '/../models/Book.php';

    function AddPost($cnx,$data): bool
    {
        $req = "INSERT INTO posts (title, content, tag) VALUES ('".$data['title']."', '".$data['content']."', '".$data['tag']."')";
        $res=$cnx->query($req);
        if($res) {
            echo "<script>console.log('Post added successfully');</script>";
            return true;
        } else {
            return false;
        }
    }
    function UpdatePost(): void
    {
        if(isset($_POST['idp'])) {
            $post = new Post($_POST['title'], $_POST['content'], $_POST['tag']);
            $post->id = $_POST['idp'];
            $reqOld = "SELECT tag FROM posts WHERE id = '" . $post->id . "'";
            $resOld = $cnx->query($reqOld);
            $OldData = $resOld->fetch();
            if($post->tag !== $OldData['tag']) {
                $tag = "'".$post->tag."'";
            } else {
                $tag = $OldData['tag'];
            }
            $req = "UPDATE posts SET title='".$post->title."', content='".$post->content."', tag='".$tag."' WHERE id='".$post->id."' where tag is not null";
            $res=$cnx->query($req);
            if($res) {
                header("Location: ../index.php?view=admin&editpost=ok");
            } else {
                echo "Error updating post";
            }
        }
    }

    function DeletePost($cnx,$id): bool
    {
        if(isset($_GET['idp'])) {
            $id_post = $_GET['idp'];
            $req = "DELETE FROM posts WHERE id = '" . $id_post . "'";
            $res=$cnx->query($req);
            if($res) {
                header("Location: ../index.php?view=admin&deletepost=ok");
            } else {
                header("Location: ../index.php?view=admin&deletepost=error");
            }
        }
    }


