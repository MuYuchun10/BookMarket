<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $notice = rawurlencode('Чтобы оформить заказ, сначала войдите или зарегистрируйтесь');
    header('Location: ../account.php?redirect=checkout.php&notice=' . $notice);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../checkout.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$street = trim($_POST['street'] ?? '');
$house = trim($_POST['house'] ?? '');
$flat = trim($_POST['flat'] ?? '');
$comment = trim($_POST['comment'] ?? '');
$cartData = $_POST['cart_data'] ?? '';

$errors = [];

if (empty($name)) {
    $errors['name'] = 'Укажите имя получателя';
}

if (empty($phone)) {
    $errors['phone'] = 'Введите номер телефона';
} else {
    $digits = preg_replace('/\D+/', '', $phone);

    if (strlen($digits) < 10) {
        $errors['phone'] = 'Введите корректный номер телефона';
    }
}

if (empty($city)) {
    $errors['city'] = 'Укажите город';
}

if (empty($street)) {
    $errors['street'] = 'Укажите улицу';
}

if (empty($house)) {
    $errors['house'] = 'Укажите номер дома';
}

$cartItems = json_decode($cartData, true);

if (!is_array($cartItems) || empty($cartItems)) {
    $errors['cart'] = 'Корзина пуста. Добавьте книги перед оформлением заказа';
}

$normalizedItems = [];

if (is_array($cartItems)) {
    foreach ($cartItems as $item) {
        $id = trim((string)($item['id'] ?? ''));
        $bookName = trim((string)($item['name'] ?? 'Книга'));
        $author = trim((string)($item['author'] ?? 'Неизвестный автор'));
        $price = max(0, (float)($item['price'] ?? 0));
        $quantity = max(1, (int)($item['quantity'] ?? 1));
        $link = trim((string)($item['link'] ?? 'catalog.php'));

        if ($id === '' || $price <= 0) {
            continue;
        }

        $normalizedItems[] = [
            'id' => $id,
            'name' => $bookName,
            'author' => $author,
            'price' => $price,
            'quantity' => $quantity,
            'link' => $link
        ];
    }
}

if (empty($normalizedItems)) {
    $errors['cart'] = 'Не удалось получить товары из корзины';
}

if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    $_SESSION['checkout_old'] = [
        'name' => $name,
        'phone' => $phone,
        'city' => $city,
        'street' => $street,
        'house' => $house,
        'flat' => $flat,
        'comment' => $comment
    ];

    header('Location: ../checkout.php');
    exit;
}

$subtotal = 0;

foreach ($normalizedItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$discount = round($subtotal * 0.1);
$total = max(0, $subtotal - $discount);

$order = [
    'id' => '#' . substr((string)time(), -6),
    'createdAt' => date('d.m.Y H:i'),
    'status' => 'Новый',
    'customerName' => $name,
    'customerEmail' => $_SESSION['user_email'] ?? '',
    'phone' => $phone,
    'address' => [
        'city' => $city,
        'street' => $street,
        'house' => $house,
        'flat' => $flat
    ],
    'comment' => $comment,
    'items' => $normalizedItems,
    'subtotal' => $subtotal,
    'discount' => $discount,
    'total' => $total
];

$_SESSION['orders'] = $_SESSION['orders'] ?? [];
array_unshift($_SESSION['orders'], $order);

unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);

$_SESSION['checkout_success'] = 'Заказ успешно оформлен';
$_SESSION['clear_cart'] = true;

header('Location: ../profile.php');
exit;