<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class UsersController extends Controller
{
    private function hashPassword(string $plain): string
    {
        return md5($plain);
    }

    public function update(): void
    {
        if (!isset($_POST['idu'])) {
            $this->redirectBack('index.php?view=admin&modif=error');
        }

        $id = (int)$_POST['idu'];
        $name = trim((string)($_POST['user_name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $passwordInput = trim((string)($_POST['password'] ?? ''));

        $oldHash = UserModel::findPasswordById($id);
        if ($oldHash === null) {
            $this->redirectBack('index.php?view=admin&modif=error');
        }

        $hash = $oldHash;
        if ($passwordInput !== '') {
            $newHash = $this->hashPassword($passwordInput);
            if ($newHash !== $oldHash) {
                $hash = $newHash;
            }
        }

        $ok = UserModel::update($id, $name, $email, $hash);
        $this->redirectBack('index.php?view=admin&modif=' . ($ok ? 'ok' : 'error'));
    }

    public function delete(): void
    {
        $id = isset($_GET['idu']) ? (int)$_GET['idu'] : 0;
        $ok = $id > 0 ? UserModel::delete($id) : false;
        $this->redirectBack('index.php?view=admin&deleteuser=' . ($ok ? 'ok' : 'error'));
    }
}

