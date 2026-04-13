<?php  
include("../config/database.php");

if(isset($_GET['idb'])){
    $id_book = $_GET['idb'];

    $requete = "DELETE FROM books WHERE id=?";
    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$id_book]);

    if ($resultat){
        header('location:../index.php?view=admin&deletebook=ok');
    } else {
        header('location:../index.php?view=admin&deletebook=error');
    }
}
?>
