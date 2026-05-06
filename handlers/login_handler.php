<?php
session_start();

require_once __DIR__ . '/../data/user_storage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../account.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirectTarget = $_POST['redirect'] ?? 'profile.php';

$allowedRedirects = ['profile.php', 'checkout.php', 'cart.php'];

if (!in_array($redirectTarget, $allowedRedirects, true)) {
    $redirectTarget = 'profile.php';
}

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
    ensureDefaultAdminUser();

    $user = getUserByEmail($email);

    if ($user && password_verify($password, $user['password_hash'] ?? '')) {
        putUserIntoSession($user);

        header('Location: ../' . $redirectTarget);
        exit;
    }

    $errors['login'] = 'Неверный email или пароль';
}

$_SESSION['login_errors'] = $errors;
$_SESSION['login_old'] = [
    'email' => $email
];

$redirectQuery = $redirectTarget !== 'profile.php'
    ? '?redirect=' . rawurlencode($redirectTarget)
    : '';

header('Location: ../account.php' . $redirectQuery);
exit;