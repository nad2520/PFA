<?php  
include("../config/database.php");

if(isset($_GET['idu'])){
    $id_user = $_GET['idu'];

    $requete = "DELETE FROM users WHERE id=?";
    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$id_user]);

    if ($resultat){
        header('location:../view/admin.php?delete=ok');
    } else {
        header('location:../view/admin.php?delete=error');
    }
}
?>
