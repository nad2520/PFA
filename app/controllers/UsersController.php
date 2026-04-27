<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class UsersController extends Controller
{
    private function hashPassword(string $plain): string
    {
        return UserModel::hashPassword($plain);
    }

    public function update(): void
    {
        $this->requireAdmin();

        if (!isset($_POST['idu'])) {
            $this->redirectBack('admin?modif=error');
        }

        $id = (int)$_POST['idu'];
        $name = trim((string)($_POST['user_name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $passwordInput = trim((string)($_POST['password'] ?? ''));

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectBack('admin?modif=error');
        }

        $oldHash = UserModel::findPasswordById($id);
        if ($oldHash === null) {
            $this->redirectBack('admin?modif=error');
        }

        $hash = $oldHash;
        if ($passwordInput !== '') {
            $newHash = $this->hashPassword($passwordInput);
            if ($newHash !== $oldHash) {
                $hash = $newHash;
            }
        }

        $ok = UserModel::update($id, $name, $email, $hash);
        $this->redirectBack('admin?modif=' . ($ok ? 'ok' : 'error'));
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = isset($_GET['idu']) ? (int)$_GET['idu'] : 0;
        $ok = $id > 0 ? UserModel::delete($id) : false;
        $this->redirectBack('admin?deleteuser=' . ($ok ? 'ok' : 'error'));
    }
}

