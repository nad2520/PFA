<?php  
include("../config/database.php");

if(isset($_POST['idu'])){
    $user_id = $_POST['idu'];
    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $password_input = trim($_POST['password']);

    // Get old password to see if it changed
    $reqOld = "SELECT password FROM users WHERE id=?";
    $stmtOld = $cnx->prepare($reqOld);
    $stmtOld->execute([$user_id]);
    $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);

    if(!empty($password_input) && md5($password_input) != $oldData['password']){
        $password = md5($password_input);
    } else {
        $password = $oldData['password'];
    }

    $requete = "UPDATE users SET nom=?, email=?, password=? WHERE id=?";
    $stmt = $cnx->prepare($requete);
    $resultat = $stmt->execute([$user_name, $email, $password, $user_id]);

    if ($resultat){
        header('location:../index.php?view=admin&modif=ok');
    } else {
        header('location:../index.php?view=admin&modif=error');
    }
}
?>
