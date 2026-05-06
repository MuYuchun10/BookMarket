<?php
session_start();

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

$errors = [];

if (empty($name)) {
    $errors['name'] = 'Введите имя';
}

if (empty($email)) {
    $errors['email'] = 'Введите email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Введите корректный email';
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

$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;
$_SESSION['profile_phone'] = $phone;
$_SESSION['profile_city'] = $city;
$_SESSION['profile_address'] = $address;
$_SESSION['profile_postcode'] = $postcode;

unset($_SESSION['profile_errors'], $_SESSION['profile_old']);

$_SESSION['profile_success'] = 'Данные профиля сохранены';

header('Location: ../profile.php');
exit;