<?php  
include("../config/database.php");

if(isset($_GET['idb'])){
    $id_book = $_GET['idb'];

    $requete = "DELETE FROM books WHERE id=?";
    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$id_book]);

    if ($resultat){
        header('location:../view/admin.php?deletebook=ok');
    } else {
        header('location:../view/admin.php?deletebook=error');
    }
}
?>
