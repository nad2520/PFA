<?php  
include("../config/database.php");

if(isset($_GET['id']) && isset($_GET['action'])){
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'review') {
        $requete = "UPDATE posts SET status='Reviewed' WHERE id=?";
    } elseif ($action == 'tag') {
        $requete = "UPDATE posts SET status='Flagged by Lumo', tag='spoiler' WHERE id=?";
    } else {
        header('location:../index.php?view=admin&tab=community');
        exit;
    }

    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$id]);

    if ($resultat){
        header('location:../index.php?view=admin&tab=community&updatepost=ok');
    } else {
        header('location:../index.php?view=admin&tab=community&updatepost=error');
    }
} else {
    header('location:../index.php?view=admin&tab=community');
}
?>
