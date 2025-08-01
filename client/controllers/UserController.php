<?php

require_once __DIR__ . '/../models/User.php';

class UserController {
    public function login() {   
        session_start();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::findByEmail($_POST['email']);
            if ($user && password_verify($_POST['password'], $user['password']))  {
                $_SESSION['user'] = $user;
                header("Location: /NHOM4_DU_AN_1/index.php");
                exit();
            } else {
                $error = "Sai thông tin đăng nhập.";
            }
        }
        include __DIR__ . '/../views/user/login.php';
    }

    public function register() {
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $exists = User::findByEmail($_POST['email']);
            if ($exists) {
                $error = "Email đã được sử dụng.";
            } else {
                if (User::create($_POST)) {
                    $success = "Đăng ký thành công, vui lòng đăng nhập!";
                    // Hiển thị thông báo rồi chuyển sang trang đăng nhập sau 2 giây
                    header("refresh:2;url=?controller=user&action=login");
                } else {
                    $error = "Đăng ký thất bại, vui lòng thử lại.";
                }
            }
        }
        include __DIR__ . '/../views/user/register.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /NHOM4_DU_AN_1/index.php");
        exit();
    }
}