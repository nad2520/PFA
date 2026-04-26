<?php
require_once __DIR__ . '/../../core/Database.php';

class PostModel
{
    public static function all(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function markReviewed(int $id): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("UPDATE posts SET status = 'Reviewed' WHERE id = ?");
            return (bool) $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function rotateTag(int $id): bool
    {
        // Mirrors the legacy behavior in controller/update_post.php
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("SELECT tag FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            $tag = $row['tag'] ?? 'discussion';

            $tags = ['discussion', 'review', 'theory', 'spoiler'];
            $idx = array_search($tag, $tags, true);
            $next = $tags[($idx === false ? 0 : ($idx + 1) % count($tags))];

            $u = $pdo->prepare("UPDATE posts SET tag = ?, status = 'Pending Admin Review' WHERE id = ?");
            return (bool) $u->execute([$next, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            return (bool) $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

