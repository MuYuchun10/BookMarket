<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../account.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

if (empty($email)) {
    $errors['email'] = 'Введите email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Некорректный email';
}

if (empty($password)) {
    $errors['password'] = 'Введите пароль';
}

if (empty($errors)) {
    if ($email === 'admin@admin.com' && $password === '123456') {
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Администратор';
        $_SESSION['user_email'] = $email;

        header('Location: ../profile.php');
        exit;
    }

    $errors['login'] = 'Неверный email или пароль';
}

$_SESSION['login_errors'] = $errors;
$_SESSION['login_old'] = [
    'email' => $email
];

header('Location: ../account.php');
exit;