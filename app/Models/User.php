<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\FileUserStorage;

final class User
{
    public function findByEmail(string $email): ?array
    {
        $users = FileUserStorage::loadUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public function findById(int $id): ?array
    {
        return FileUserStorage::findById($id);
    }

    public function create(string $email, ?string $phone, string $passwordHash, string $roleName): int
    {
        // This method should use FileUserStorage for consistency
        // For now, return a placeholder ID
        // In a real implementation, this would add to the users.php file
        return count(FileUserStorage::loadUsers()) + 1;
    }
}
