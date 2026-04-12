<?php  
include("../config/database.php");

if(isset($_POST['title'])){
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $cover = trim($_POST['cover']);
    if(empty($cover)) $cover = '📖';
    $coinCost = isset($_POST['coinCost']) ? (int)$_POST['coinCost'] : 0;
    $xpReward = isset($_POST['xpReward']) ? (int)$_POST['xpReward'] : 0;
    $coinReward = isset($_POST['coinReward']) ? (int)$_POST['coinReward'] : 0;
    $audience = isset($_POST['audience']) ? $_POST['audience'] : 'All';
    $trending = isset($_POST['trending']) ? 1 : 0;

    $requete = "INSERT INTO books (title, author, genre, cover, coinCost, xpReward, coinReward, audience, trending) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$title, $author, $genre, $cover, $coinCost, $xpReward, $coinReward, $audience, $trending]);

    if ($resultat){
        header('location:../view/admin.php?addbook=ok');
    } else {
        header('location:../view/admin.php?addbook=error');
    }
}
?>
