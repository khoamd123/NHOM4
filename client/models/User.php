<?php
require_once __DIR__ . '/../../db.php';
class User {
    public static function findByEmail($email) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function create($data) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, status, created_at) VALUES (?, ?, ?, ?, 0, 1, NOW())");
        return $stmt->execute([
            $data['name'], $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['phone']
        ]);
    }
}
