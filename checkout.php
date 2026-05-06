<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $notice = rawurlencode('Чтобы оформить заказ, сначала войдите или зарегистрируйтесь');
    header('Location: account.php?redirect=checkout.php&notice=' . $notice);
    exit;
}

$checkoutErrors = $_SESSION['checkout_errors'] ?? [];
$checkoutOld = $_SESSION['checkout_old'] ?? [];

unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);
?>
<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Оформление заказа в интернет-магазине BookMarket." name="description" />
    <title>Оформление заказа - BookMarket</title>
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
        <section aria-labelledby="checkout-title" class="checkout-page">
            <div class="container">
                <p class="checkout-page__eyebrow">Заказ</p>
                <h1 class="checkout-page__title" id="checkout-title">
                    Заполните данные для доставки
                </h1>
                <div class="checkout-layout">
                    <section aria-labelledby="delivery-title" class="checkout-form-card">
                        <h2 class="visually-hidden" id="delivery-title">Данные для доставки</h2>
                        <form action="handlers/checkout_handler.php" class="checkout-form" id="checkout-form"
                            method="post" novalidate>
                            <input id="checkout-cart-data" name="cart_data" type="hidden" value="" />

                            <?php if (isset($checkoutErrors['cart'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($checkoutErrors['cart']); ?>
                                </div>
                            <?php endif; ?>

                            <div aria-live="polite" class="checkout-form__success"></div>
                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-name">Имя получателя</label>
                                <input autocomplete="name"
                                    class="checkout-form__input <?php echo isset($checkoutErrors['name']) ? 'input-error' : ''; ?>"
                                    id="checkout-name" name="name" placeholder="Имя получателя" required type="text"
                                    value="<?php echo htmlspecialchars($checkoutOld['name'] ?? ''); ?>" />
                                <?php if (isset($checkoutErrors['name'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($checkoutErrors['name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-phone">Номер телефона</label>
                                <input autocomplete="tel"
                                    class="checkout-form__input <?php echo isset($checkoutErrors['phone']) ? 'input-error' : ''; ?>"
                                    id="checkout-phone" name="phone" placeholder="Номер телефона" required type="tel"
                                    value="<?php echo htmlspecialchars($checkoutOld['phone'] ?? ''); ?>" />
                                <?php if (isset($checkoutErrors['phone'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($checkoutErrors['phone']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-city">Город</label>
                                <input autocomplete="address-level2"
                                    class="checkout-form__input <?php echo isset($checkoutErrors['city']) ? 'input-error' : ''; ?>"
                                    id="checkout-city" name="city" placeholder="Город" required type="text"
                                    value="<?php echo htmlspecialchars($checkoutOld['city'] ?? ''); ?>" />
                                <?php if (isset($checkoutErrors['city'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($checkoutErrors['city']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-street">Улица</label>
                                <input autocomplete="street-address"
                                    class="checkout-form__input <?php echo isset($checkoutErrors['street']) ? 'input-error' : ''; ?>"
                                    id="checkout-street" name="street" placeholder="Улица" required type="text"
                                    value="<?php echo htmlspecialchars($checkoutOld['street'] ?? ''); ?>" />
                                <?php if (isset($checkoutErrors['street'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($checkoutErrors['street']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-house">Дом</label>
                                <input
                                    class="checkout-form__input <?php echo isset($checkoutErrors['house']) ? 'input-error' : ''; ?>"
                                    id="checkout-house" name="house" placeholder="Дом" required type="text"
                                    value="<?php echo htmlspecialchars($checkoutOld['house'] ?? ''); ?>" />
                                <?php if (isset($checkoutErrors['house'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($checkoutErrors['house']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-flat">Квартира</label>
                                <input class="checkout-form__input" id="checkout-flat" name="flat"
                                    placeholder="Квартира" type="text"
                                    value="<?php echo htmlspecialchars($checkoutOld['flat'] ?? ''); ?>" />
                            </div>

                            <div class="checkout-form__group checkout-form__group--full">
                                <label class="visually-hidden" for="checkout-comment">Комментарий к заказу</label>
                                <textarea class="checkout-form__textarea" id="checkout-comment" name="comment"
                                    placeholder="Комментарий к заказу"><?php echo htmlspecialchars($checkoutOld['comment'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="checkout-options">
                                <div class="checkout-option">Способ оплаты: при получении</div>
                                <div class="checkout-option">Доставка: курьером</div>
                            </div>
                        </form>
                    </section>
                    <aside class="checkout-summary">
                        <h2 class="checkout-summary__title">Ваш заказ</h2>
                        <div class="checkout-summary__rows" id="checkout-summary-items">
                            <div class="checkout-summary__row checkout-summary__row--accent"><span>Товары</span><span
                                    id="checkout-subtotal">0 ₽</span></div>
                            <div class="checkout-summary__row"><span>Скидка 10%</span><span id="checkout-discount">0
                                    ₽</span></div>
                        </div>
                        <div class="checkout-summary__total">
                            <span>Итого</span>
                            <span id="checkout-total">0 ₽</span>
                        </div>
                        <button class="btn btn--primary checkout-summary__button" form="checkout-form"
                            id="checkout-submit" type="submit">
                            Подтвердить заказ
                        </button>
                    </aside>
                </div>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="js/books-data.js"></script>
    <script src="js/main.js"></script>
</body>

</html>