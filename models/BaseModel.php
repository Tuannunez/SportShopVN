<?php

class BaseModel
{
    protected $table;
    protected $pdo;

    // Kết nối CSDL
    public function __construct()
    {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);

        try {
            $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
        } catch (PDOException $e) {
            // Xử lý lỗi kết nối
            die("Kết nối cơ sở dữ liệu thất bại: {$e->getMessage()}. Vui lòng thử lại sau.");
        }
    }

    // Hủy kết nối CSDL
    public function __destruct()
    {
        $this->pdo = null;
    }
    // Lấy tất cả user
    public static function getAllUsers()
    {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        $conn->set_charset('utf8mb4');
        $result = $conn->query("SELECT id, username, email, role, locked FROM users ORDER BY id ASC");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $conn->close();
        return $users;
    }

    // Đổi vai trò user
    public static function updateUserRole($userId, $newRole)
    {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        $conn->set_charset('utf8mb4');
        $userId = (int)$userId;
        $newRole = $conn->real_escape_string($newRole);
        $conn->query("UPDATE users SET role='$newRole' WHERE id=$userId");
        $conn->close();
    }

    // Khóa/mở khóa user
    public static function toggleUserLock($userId)
    {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        $conn->set_charset('utf8mb4');
        $userId = (int)$userId;
        $result = $conn->query("SELECT locked FROM users WHERE id=$userId");
        $locked = 0;
        if ($row = $result->fetch_assoc()) {
            $locked = $row['locked'] ? 0 : 1;
        }
        $conn->query("UPDATE users SET locked=$locked WHERE id=$userId");
        $conn->close();
    }
}
