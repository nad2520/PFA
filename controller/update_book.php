<?php  
include("../config/database.php");

if(isset($_POST['idb'])){
    $idb = $_POST['idb'];
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

    $requete = "UPDATE books SET title=?, author=?, genre=?, cover=?, coinCost=?, xpReward=?, coinReward=?, audience=?, trending=? WHERE id=?";
    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$title, $author, $genre, $cover, $coinCost, $xpReward, $coinReward, $audience, $trending, $idb]);

    if ($resultat){
        header('location:../index.php?view=admin&editbook=ok');
    } else {
        header('location:../index.php?view=admin&editbook=error');
    }
}
?>
