<?php
include_once __DIR__ . '/../models/User.php';
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../models/UserModel.php';


    function AddUser($cnx,$data): bool
     {
        $password=md5($data['password']);
        $req = "INSERT INTO users (nom, email, password, role) VALUES ('".$data['nom']."', '".$data['email']."', '".$password."', '".$data['role']."')";
        $res=$cnx->query($req);
        if($res) {
            echo "<script>console.log('User added successfully');</script>";
            return true;
        } else {
           return false;
        }
     }
    function update(): void
    {
        global $cnx;
        if (isset($_POST['idu'])) {
            $user = new User($_POST['user_name'], $_POST['email'], $_POST['password']);
            $user->id = $_POST['idu'];
            $reqOld = "SELECT password FROM users WHERE id = '" . $user->id . "'";
            $resOld = $cnx->query($reqOld);
            $OldData = $resOld->fetch();

            if($user->password !== $OldData['password']) {
                $password=md5($user->password);
            } else {
                $password=$OldData['password'];
            }
            $req="UPDATE users SET nom='".$user->nom."', email='".$user->email."', password='".$password."' WHERE id=".$user->id;
            $res=$cnx->query($req);
            if($res) {
                header("Location: index.php?view=admin&modif=ok");
            } else {
                echo "Error updating user";
            }
        }
    }

    function delete(): void
    {
    global $cnx;
    if (isset($_GET['idu'])) {
        $id_user = $_GET['idu'];

        $res = $cnx->query("DELETE FROM users WHERE id = " . $id_user);
        if ($res) {
            header("Location: index.php?view=admin&deleteuser=ok");
        } else {
            header("Location: index.php?view=admin&deleteuser=error");
        }
    }
}

    

