<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/PostModel.php';

class PostsController extends Controller
{
    public function update(): void
    {
        $this->requireAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $action = $_GET['action'] ?? '';
        if ($id <= 0) {
            $this->redirectBack('admin?post=error');
        }

        $ok = false;
        if ($action === 'review') $ok = PostModel::markReviewed($id);
        if ($action === 'tag') $ok = PostModel::rotateTag($id);

        $this->redirectBack('admin?post=' . ($ok ? 'ok' : 'error'));
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $ok = $id > 0 ? PostModel::delete($id) : false;
        $this->redirectBack('admin?postdelete=' . ($ok ? 'ok' : 'error'));
    }
}

