<?php
class Profile extends Model
{
    public function getByUsername(string $username): ?array
    {
        return $this->fetchOne(
            'SELECT p.*, a.email FROM profile p
             JOIN account a ON a.id = p.accountId
             WHERE p.username = :username',
            [':username' => $username]
        );
    }

    public function getById(string $id): ?array
    {
        return $this->fetchOne(
            'SELECT p.*, a.email FROM profile p
             JOIN account a ON a.id = p.accountId
             WHERE p.id = :id',
            [':id' => $id]
        );
    }

    public function authenticate(string $username, string $password): ?array
    {
        $profile = $this->getByUsername($username);
        if (!$profile) {
            return null;
        }

        $account = $this->fetchOne('SELECT passwordHash, id FROM account WHERE id = :id', [':id' => $profile['accountId']]);
        if (!$account) {
            return null;
        }

        if (password_verify($password, $account['passwordHash'])) {
            return $profile;
        }

        return null;
    }

    public function create(string $email, string $username, string $password): ?array
    {
        if ($this->getByUsername($username)) {
            return null;
        }

        $accountId = $this->generateUuid();
        $profileId = $this->generateUuid();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $this->db->beginTransaction();
        try {
            $this->execute(
                'INSERT INTO account (id, email, passwordHash, status) VALUES (:id, :email, :hash, :status)',
                [
                    ':id' => $accountId,
                    ':email' => $email,
                    ':hash' => $passwordHash,
                    ':status' => 'ACTIVE',
                ]
            );

            $this->execute(
                'INSERT INTO profile (id, accountId, username, role, currentXp, currentCoins) VALUES (:id, :accountId, :username, :role, :xp, :coins)',
                [
                    ':id' => $profileId,
                    ':accountId' => $accountId,
                    ':username' => $username,
                    ':role' => 'USER',
                    ':xp' => 0,
                    ':coins' => 0,
                ]
            );

            $this->db->commit();
            return $this->getById($profileId);
        } catch (Exception $e) {
            $this->db->rollBack();
            return null;
        }
    }

    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
