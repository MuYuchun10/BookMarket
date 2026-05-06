<?php
session_start();

require_once __DIR__ . '/../data/user_storage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../account.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$redirectTarget = $_POST['redirect'] ?? 'profile.php';

$allowedRedirects = ['profile.php', 'checkout.php', 'cart.php'];

if (!in_array($redirectTarget, $allowedRedirects, true)) {
    $redirectTarget = 'profile.php';
}

$errors = [];

if (empty($name)) {
    $errors['name'] = 'Имя обязательно';
}

if (empty($email)) {
    $errors['email'] = 'Email обязателен';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Некорректный email';
} elseif (getUserByEmail($email)) {
    $errors['email'] = 'Пользователь с таким email уже зарегистрирован';
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

    $redirectQuery = $redirectTarget !== 'profile.php'
        ? '?redirect=' . rawurlencode($redirectTarget)
        : '';

    header('Location: ../account.php' . $redirectQuery);
    exit;
}

$user = saveUserData($email, [
    'id' => time(),
    'name' => $name,
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    'phone' => '',
    'city' => '',
    'address' => '',
    'postcode' => ''
]);

putUserIntoSession($user);

header('Location: ../' . $redirectTarget);
exit;