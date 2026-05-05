<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../account.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$errors = [];

if (empty($name)) {
    $errors['name'] = 'Имя обязательно';
}

if (empty($email)) {
    $errors['email'] = 'Email обязателен';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Некорректный email';
}

if (empty($password)) {
    $errors['password'] = 'Пароль обязателен';
} elseif (strlen($password) < 6) {
    $errors['password'] = 'Пароль должен быть не менее 6 символов';
}

if ($password !== $confirm) {
    $errors['confirm'] = 'Пароли не совпадают';
}

if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_old'] = [
        'name' => $name,
        'email' => $email
    ];

    header('Location: ../account.php');
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;

header('Location: ../profile.php');
exit;