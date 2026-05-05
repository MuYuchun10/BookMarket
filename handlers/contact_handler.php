<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contacts.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

if (empty($name)) {
    $errors['name'] = 'Имя обязательно';
}

if (empty($email)) {
    $errors['email'] = 'Email обязателен';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Некорректный email';
}

if (empty($subject)) {
    $errors['subject'] = 'Тема сообщения обязательна';
}

if (empty($message)) {
    $errors['message'] = 'Сообщение обязательно';
}

if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_old'] = $_POST;

    header('Location: ../contacts.php');
    exit;
}

$_SESSION['contact_success'] = 'Сообщение отправлено!';

header('Location: ../contacts.php');
exit;