<?php
session_start();

require_once __DIR__ . '/../data/user_storage.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../account.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../profile.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$address = trim($_POST['address'] ?? '');
$postcode = trim($_POST['postcode'] ?? '');

$currentEmail = $_SESSION['user_email'] ?? '';
$errors = [];

if (empty($name)) {
    $errors['name'] = 'Введите имя';
}

if (empty($email)) {
    $errors['email'] = 'Введите email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Введите корректный email';
} elseif (
    normalizeUserEmail($email) !== normalizeUserEmail($currentEmail)
    && getUserByEmail($email)
) {
    $errors['email'] = 'Пользователь с таким email уже существует';
}

if (!empty($phone)) {
    $digits = preg_replace('/\D+/', '', $phone);

    if (strlen($digits) < 10) {
        $errors['phone'] = 'Введите корректный телефон';
    }
}

if (!empty($postcode) && !preg_match('/^\d{5,6}$/', $postcode)) {
    $errors['postcode'] = 'Введите корректный почтовый индекс';
}

if (!empty($errors)) {
    $_SESSION['profile_errors'] = $errors;
    $_SESSION['profile_old'] = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'city' => $city,
        'address' => $address,
        'postcode' => $postcode
    ];

    header('Location: ../profile.php');
    exit;
}

$oldUser = getUserByEmail($currentEmail) ?? [];

if (
    !empty($currentEmail)
    && normalizeUserEmail($currentEmail) !== normalizeUserEmail($email)
) {
    deleteUserByEmail($currentEmail);
}

$user = saveUserData($email, [
    'id' => $_SESSION['user_id'],
    'name' => $name,
    'password_hash' => $oldUser['password_hash'] ?? password_hash('123456', PASSWORD_DEFAULT),
    'phone' => $phone,
    'city' => $city,
    'address' => $address,
    'postcode' => $postcode
]);

putUserIntoSession($user);

unset($_SESSION['profile_errors'], $_SESSION['profile_old']);

$_SESSION['profile_success'] = 'Данные профиля сохранены';

header('Location: ../profile.php');
exit;