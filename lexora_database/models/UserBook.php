<?php
class UserBook extends Model
{
    public function getByProfile(string $profileId): array
    {
        return $this->fetchAll(
            'SELECT ub.*, b.title, b.coverUrl, b.coinCost, b.isAdulte FROM userbook ub
             JOIN book b ON b.id = ub.bookId
             WHERE ub.profileId = :profileId
             ORDER BY ub.status DESC, b.title ASC',
            [':profileId' => $profileId]
        );
    }

    public function getWishlist(string $profileId): array
    {
        return $this->fetchAll(
            'SELECT ub.*, b.title, b.coverUrl, b.coinCost FROM userbook ub
             JOIN book b ON b.id = ub.bookId
             WHERE ub.profileId = :profileId AND ub.status = :status
             ORDER BY b.title ASC',
            [':profileId' => $profileId, ':status' => 'WISHLIST']
        );
    }

    public function addToLibrary(string $profileId, string $bookId, string $status = 'READING'): bool
    {
        return $this->execute(
            'REPLACE INTO userbook (profileId, bookId, status, progressPercent, isPurchased, addedAt, updatedAt) VALUES (:profileId, :bookId, :status, :progress, :purchased, NOW(), NOW())',
            [
                ':profileId' => $profileId,
                ':bookId' => $bookId,
                ':status' => $status,
                ':progress' => 0,
                ':purchased' => 1,
            ]
        );
    }

    public function addToWishlist(string $profileId, string $bookId): bool
    {
        return $this->execute(
            'REPLACE INTO userbook (profileId, bookId, status, progressPercent, isPurchased, addedAt, updatedAt) VALUES (:profileId, :bookId, :status, :progress, :purchased, NOW(), NOW())',
            [
                ':profileId' => $profileId,
                ':bookId' => $bookId,
                ':status' => 'WISHLIST',
                ':progress' => 0,
                ':purchased' => 0,
            ]
        );
    }

    public function updateProgress(string $profileId, string $bookId, float $progress): bool
    {
        return $this->execute(
            'UPDATE userbook SET progressPercent = :progress, updatedAt = NOW() WHERE profileId = :profileId AND bookId = :bookId',
            [':progress' => $progress, ':profileId' => $profileId, ':bookId' => $bookId]
        );
    }

    public function removeFromWishlist(string $profileId, string $bookId): bool
    {
        return $this->execute(
            'DELETE FROM userbook WHERE profileId = :profileId AND bookId = :bookId AND status = :status',
            [':profileId' => $profileId, ':bookId' => $bookId, ':status' => 'WISHLIST']
        );
    }
}
