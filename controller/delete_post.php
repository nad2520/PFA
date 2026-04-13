<?php  
include("../config/database.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $requete = "DELETE FROM posts WHERE id=?";
    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$id]);

    if ($resultat){
        header('location:../index.php?view=admin&tab=community&deletepost=ok');
    } else {
        header('location:../index.php?view=admin&tab=community&deletepost=error');
    }
} else {
    header('location:../index.php?view=admin&tab=community');
}
?>
