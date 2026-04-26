<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/QuestModel.php';

class QuestService
{
    public static function onPagesRead(int $userId, int $pagesDelta): void
    {
        self::applyTypeProgress($userId, 'read_pages_total', $pagesDelta);
    }

    public static function onBookCompleted(int $userId, int $countDelta = 1): void
    {
        self::applyTypeProgress($userId, 'complete_books_count', $countDelta);
    }

    public static function onBookAddedToList(int $userId, int $countDelta = 1): void
    {
        self::applyTypeProgress($userId, 'add_to_list_count', $countDelta);
    }

    private static function applyTypeProgress(int $userId, string $questType, int $delta): void
    {
        if ($userId <= 0 || $delta <= 0) {
            return;
        }
        $quests = QuestModel::activeByType($questType);
        if (count($quests) === 0) {
            return;
        }

        foreach ($quests as $quest) {
            self::applyQuestDelta($userId, $quest, $delta);
        }
    }

    private static function applyQuestDelta(int $userId, array $quest, int $delta): void
    {
        $questId = (int)($quest['id'] ?? 0);
        if ($questId <= 0) {
            return;
        }

        $target = max(1, (int)($quest['target_value'] ?? 1));
        $coinReward = max(0, (int)($quest['coins_reward'] ?? 0));
        $xpReward = max(0, (int)($quest['xp_reward'] ?? 0));
        $questKey = (string)($quest['quest_key'] ?? '');

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $pdo->prepare(
                'INSERT INTO user_quest_progress (user_id, quest_id, progress_value, is_completed)
                 VALUES (?, ?, 0, 0)
                 ON DUPLICATE KEY UPDATE user_id = user_id'
            )->execute([$userId, $questId]);

            $pdo->prepare(
                'UPDATE user_quest_progress
                 SET progress_value = progress_value + ?
                 WHERE user_id = ? AND quest_id = ?'
            )->execute([$delta, $userId, $questId]);

            $sel = $pdo->prepare(
                'SELECT progress_value, is_completed, reward_granted_at
                 FROM user_quest_progress
                 WHERE user_id = ? AND quest_id = ?
                 FOR UPDATE'
            );
            $sel->execute([$userId, $questId]);
            $row = $sel->fetch(PDO::FETCH_ASSOC) ?: ['progress_value' => 0, 'is_completed' => 0, 'reward_granted_at' => null];
            $progress = (int)($row['progress_value'] ?? 0);
            $isCompleted = (int)($row['is_completed'] ?? 0) === 1;
            $grantedAt = $row['reward_granted_at'] ?? null;

            if ($progress >= $target && !$isCompleted) {
                $pdo->prepare(
                    'UPDATE user_quest_progress
                     SET is_completed = 1, completed_at = COALESCE(completed_at, NOW())
                     WHERE user_id = ? AND quest_id = ?'
                )->execute([$userId, $questId]);
            }

            if ($progress >= $target && $grantedAt === null) {
                if ($coinReward > 0 || $xpReward > 0) {
                    $pdo->prepare('UPDATE users SET coins = coins + ?, xp = xp + ? WHERE id = ?')
                        ->execute([$coinReward, $xpReward, $userId]);
                    $pdo->prepare('UPDATE users SET level = GREATEST(1, FLOOR(xp / 500) + 1) WHERE id = ?')
                        ->execute([$userId]);
                    if ($coinReward > 0) {
                        $pdo->prepare(
                            'INSERT INTO economy_logs (user_id, log_date, coins_earned, event_type)
                             VALUES (?, CURDATE(), ?, ?)'
                        )->execute([$userId, $coinReward, 'quest_auto_completion']);
                    }
                }

                if ($questKey !== '') {
                    $pdo->prepare(
                        'INSERT IGNORE INTO user_quest_rewards (user_id, quest_key, coins_rewarded) VALUES (?, ?, ?)'
                    )->execute([$userId, $questKey, $coinReward]);
                }

                $pdo->prepare(
                    'UPDATE user_quest_progress
                     SET reward_granted_at = NOW(), is_completed = 1, completed_at = COALESCE(completed_at, NOW())
                     WHERE user_id = ? AND quest_id = ?'
                )->execute([$userId, $questId]);
            }

            $pdo->commit();
        } catch (Throwable) {
            $pdo->rollBack();
        }
    }
}
