<?php
require_once __DIR__ . '/../models/BaseModel.php';

class AdminUserController {
    public function index() {
        $users = BaseModel::getAllUsers();
        include __DIR__ . '/../views/admin/admin_users.php';
    }

    public function changeRole($userId, $newRole) {
        BaseModel::updateUserRole($userId, $newRole);
        header('Location: ?controller=admin_user');
        exit();
    }

    public function toggleLock($userId) {
        BaseModel::toggleUserLock($userId);
        header('Location: ?controller=admin_user');
        exit();
    }
}
