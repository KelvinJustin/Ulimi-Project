<?php

namespace App\Core;

class FileUserStorage
{
    private static $users = null;
    private static $filePath = null;

    private static function getFilePath(): string
    {
        if (self::$filePath === null) {
            self::$filePath = __DIR__ . '/../../storage/users.php';
        }
        return self::$filePath;
    }

    public static function loadUsers(): array
    {
        if (self::$users === null) {
            $file = self::getFilePath();
            if (file_exists($file)) {
                self::$users = include $file;
            } else {
                self::$users = [];
            }
        }
        return self::$users;
    }

    public static function findByEmail(string $email): ?array
    {
        $users = self::loadUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public static function findById(int $id): ?array
    {
        $users = self::loadUsers();
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public static function verifyCredentials(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public static function createUser(array $userData): bool
    {
        $users = self::loadUsers();
        
        // Check if email already exists
        if (self::findByEmail($userData['email'])) {
            return false;
        }

        // Generate new ID
        $newId = count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1;
        
        $newUser = [
            'id' => $newId,
            'email' => $userData['email'],
            'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
            'display_name' => $userData['display_name'] ?? $userData['email'],
            'role' => $userData['role'] ?? 'buyer',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $users[] = $newUser;
        return self::saveUsers($users);
    }

    public static function deleteUser(int $userId): bool
    {
        $users = self::loadUsers();
        
        // Find the user to delete
        $userToDelete = null;
        foreach ($users as $user) {
            if ($user['id'] === $userId) {
                $userToDelete = $user;
                break;
            }
        }
        
        // Prevent deletion of admin accounts
        if ($userToDelete && $userToDelete['role'] === 'admin') {
            return false; // Cannot delete admin accounts
        }
        
        // Find and remove the user
        $filteredUsers = array_filter($users, function($user) use ($userId) {
            return $user['id'] !== $userId;
        });
        
        // Check if user was found and removed
        if (count($filteredUsers) === count($users)) {
            return false; // User not found
        }
        
        return self::saveUsers(array_values($filteredUsers));
    }

    public static function saveUsers(array $users): bool
    {
        $file = self::getFilePath();
        $content = "<?php\nreturn " . var_export($users, true) . ";\n";
        $result = file_put_contents($file, $content) !== false;
        
        // Clear the cache after saving so next loadUsers() gets fresh data
        if ($result) {
            self::$users = null;
        }
        
        return $result;
    }
}
