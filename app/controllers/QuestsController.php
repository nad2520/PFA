<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/QuestModel.php';

class QuestsController extends Controller
{
    public function create(): void
    {
        $this->requireAdmin();
        if (!isset($_POST['quest_key'], $_POST['title'])) {
            $this->redirectBack('admin?addquest=error');
        }

        $ok = QuestModel::create([
            'quest_key' => (string)$_POST['quest_key'],
            'title' => (string)$_POST['title'],
            'description' => (string)($_POST['description'] ?? ''),
            'quest_type' => (string)($_POST['quest_type'] ?? 'read_pages_total'),
            'target_value' => (int)($_POST['target_value'] ?? 1),
            'coins_reward' => (int)($_POST['coins_reward'] ?? 0),
            'xp_reward' => (int)($_POST['xp_reward'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        $this->redirectBack('admin?addquest=' . ($ok ? 'ok' : 'error'));
    }

    public function update(): void
    {
        $this->requireAdmin();
        if (!isset($_POST['idq'], $_POST['title'])) {
            $this->redirectBack('admin?editquest=error');
        }

        $id = (int)$_POST['idq'];
        $ok = QuestModel::update($id, [
            'title' => (string)$_POST['title'],
            'description' => (string)($_POST['description'] ?? ''),
            'quest_type' => (string)($_POST['quest_type'] ?? 'read_pages_total'),
            'target_value' => (int)($_POST['target_value'] ?? 1),
            'coins_reward' => (int)($_POST['coins_reward'] ?? 0),
            'xp_reward' => (int)($_POST['xp_reward'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
        ]);

        $this->redirectBack('admin?editquest=' . ($ok ? 'ok' : 'error'));
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $id = 0;
        if (isset($_POST['idq'])) {
            $id = (int)$_POST['idq'];
        } elseif (isset($_GET['idq'])) {
            $id = (int)$_GET['idq'];
        }
        $ok = $id > 0 ? QuestModel::delete($id) : false;
        $this->redirectBack('admin?deletequest=' . ($ok ? 'ok' : 'error'));
    }
}
