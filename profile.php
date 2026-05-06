<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit;
}

function formatPricePhp($value)
{
    return number_format(max(0, (float) $value), 0, '', ' ') . ' ₽';
}

function pluralizeBooksPhp($count)
{
    $count = abs((int) $count) % 100;
    $lastDigit = $count % 10;

    if ($count > 10 && $count < 20) {
        return 'книг';
    }

    if ($lastDigit > 1 && $lastDigit < 5) {
        return 'книги';
    }

    if ($lastDigit === 1) {
        return 'книга';
    }

    return 'книг';
}

$profileErrors = $_SESSION['profile_errors'] ?? [];
$profileOld = $_SESSION['profile_old'] ?? [];
$profileSuccess = $_SESSION['profile_success'] ?? '';
$checkoutSuccess = $_SESSION['checkout_success'] ?? '';
$shouldClearCart = $_SESSION['clear_cart'] ?? false;

unset(
    $_SESSION['profile_errors'],
    $_SESSION['profile_old'],
    $_SESSION['profile_success'],
    $_SESSION['checkout_success'],
    $_SESSION['clear_cart']
);

$userName = $profileOld['name'] ?? ($_SESSION['user_name'] ?? 'Пользователь');
$userEmail = $profileOld['email'] ?? ($_SESSION['user_email'] ?? '');
$userPhone = $profileOld['phone'] ?? ($_SESSION['profile_phone'] ?? '');
$userCity = $profileOld['city'] ?? ($_SESSION['profile_city'] ?? '');
$userAddress = $profileOld['address'] ?? ($_SESSION['profile_address'] ?? '');
$userPostcode = $profileOld['postcode'] ?? ($_SESSION['profile_postcode'] ?? '');

$orders = $_SESSION['orders'] ?? [];
?>
<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Личный кабинет пользователя BookMarket." name="description" />
    <title>Личный кабинет - BookMarket</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&amp;display=swap"
        rel="stylesheet" />
    <link href="css/normalize.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
</head>

<body class="page">
    <?php include 'includes/header.php'; ?>
    <main class="main">
        <section aria-labelledby="profile-title" class="profile-page">
            <div class="container">
                <div class="profile-hero">
                    <div class="profile-hero__info">
                        <div aria-hidden="true" class="profile-hero__avatar">👤</div>
                        <div class="profile-hero__content">
                            <h1 class="profile-hero__title" id="profile-title">Личный кабинет</h1>
                            <p class="profile-hero__text">
                                <?php echo htmlspecialchars($userName); ?>
                                <?php if (!empty($userEmail)): ?>
                                    · <?php echo htmlspecialchars($userEmail); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <a class="btn btn--primary profile-hero__button" href="handlers/logout.php">
                        Выйти из аккаунта
                    </a>
                </div>
                <section aria-labelledby="profile-data-title" class="profile-card">
                    <div class="profile-section-heading">
                        <span aria-hidden="true" class="profile-section-heading__icon">👤</span>
                        <h2 class="profile-section-heading__title" id="profile-data-title">Данные профиля</h2>
                    </div>
                    <?php if (!empty($profileSuccess)): ?>
                        <div class="contact-form__success">
                            <?php echo htmlspecialchars($profileSuccess); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($checkoutSuccess)): ?>
                        <div class="contact-form__success">
                            <?php echo htmlspecialchars($checkoutSuccess); ?>
                        </div>
                    <?php endif; ?>

                    <form action="handlers/profile_handler.php" class="profile-form" id="profile-form" method="post"
                        novalidate>
                        <label class="visually-hidden" for="profile-name">Имя</label>
                        <input autocomplete="name"
                            class="profile-form__input <?php echo isset($profileErrors['name']) ? 'input-error' : ''; ?>"
                            id="profile-name" name="name" placeholder="Имя" type="text"
                            value="<?php echo htmlspecialchars($userName); ?>" />
                        <?php if (isset($profileErrors['name'])): ?>
                            <div class="error-message"><?php echo htmlspecialchars($profileErrors['name']); ?></div>
                        <?php endif; ?>

                        <label class="visually-hidden" for="profile-email">Email</label>
                        <input autocomplete="email"
                            class="profile-form__input <?php echo isset($profileErrors['email']) ? 'input-error' : ''; ?>"
                            id="profile-email" name="email" placeholder="Email" type="email"
                            value="<?php echo htmlspecialchars($userEmail); ?>" />
                        <?php if (isset($profileErrors['email'])): ?>
                            <div class="error-message"><?php echo htmlspecialchars($profileErrors['email']); ?></div>
                        <?php endif; ?>

                        <label class="visually-hidden" for="profile-phone">Телефон</label>
                        <input autocomplete="tel"
                            class="profile-form__input <?php echo isset($profileErrors['phone']) ? 'input-error' : ''; ?>"
                            id="profile-phone" name="phone" placeholder="Телефон" type="tel"
                            value="<?php echo htmlspecialchars($userPhone); ?>" />
                        <?php if (isset($profileErrors['phone'])): ?>
                            <div class="error-message">
                                <?php echo htmlspecialchars($profileErrors['phone']); ?>
                            </div>
                        <?php endif; ?>

                        <label class="visually-hidden" for="profile-city">Город</label>
                        <input autocomplete="address-level2" class="profile-form__input" id="profile-city" name="city"
                            placeholder="Город" type="text" value="<?php echo htmlspecialchars($userCity); ?>" />

                        <label class="visually-hidden" for="profile-address">Адрес</label>
                        <input autocomplete="street-address" class="profile-form__input" id="profile-address"
                            name="address" placeholder="Адрес" type="text"
                            value="<?php echo htmlspecialchars($userAddress); ?>" />

                        <label class="visually-hidden" for="profile-postcode">Почтовый индекс</label>
                        <input autocomplete="postal-code"
                            class="profile-form__input <?php echo isset($profileErrors['postcode']) ? 'input-error' : ''; ?>"
                            id="profile-postcode" name="postcode" placeholder="Почтовый индекс" type="text"
                            value="<?php echo htmlspecialchars($userPostcode); ?>" />
                        <?php if (isset($profileErrors['postcode'])): ?>
                            <div class="error-message">
                                <?php echo htmlspecialchars($profileErrors['postcode']); ?>
                            </div>
                        <?php endif; ?>
                    </form>
                    <button class="btn btn--accent profile-form__button" form="profile-form" type="submit">
                        Сохранить изменения
                    </button>
                </section>
                <section aria-labelledby="profile-orders-title" class="profile-card">
                    <div class="profile-section-heading">
                        <span aria-hidden="true" class="profile-section-heading__icon">📦</span>
                        <h2 class="profile-section-heading__title" id="profile-orders-title">История заказов</h2>
                    </div>
                    <div class="orders-list" id="profile-orders" data-orders-source="php">
                        <?php if (empty($orders)): ?>
                            <p class="orders-list__empty">История заказов пока пуста.</p>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <?php
                                $items = $order['items'] ?? [];
                                $booksCount = 0;

                                foreach ($items as $item) {
                                    $booksCount += (int) ($item['quantity'] ?? 0);
                                }
                                ?>
                                <article class="order-row">
                                    <div class="order-row__id">
                                        <?php echo htmlspecialchars($order['id'] ?? ''); ?>
                                    </div>
                                    <div class="order-row__count">
                                        <?php echo $booksCount; ?>
                                        <?php echo pluralizeBooksPhp($booksCount); ?>
                                    </div>
                                    <div class="order-row__price">
                                        <?php echo formatPricePhp($order['total'] ?? 0); ?>
                                    </div>
                                    <div class="order-row__status">
                                        <?php echo htmlspecialchars($order['status'] ?? 'Новый'); ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <?php if ($shouldClearCart): ?>
        <script>
            localStorage.removeItem('bookMarketCart');
            localStorage.removeItem('bookMarketOrders');
        </script>
    <?php endif; ?>
    <script src="js/books-data.js"></script>
    <script src="js/main.js"></script>
</body>

</html>