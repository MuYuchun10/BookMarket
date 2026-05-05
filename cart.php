<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Корзина интернет-магазина BookMarket." name="description" />
    <title>Корзина - BookMarket</title>
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
        <section aria-labelledby="cart-title" class="cart-page">
            <div class="container">
                <p class="cart-page__eyebrow">Корзина</p>
                <h1 class="cart-page__title" id="cart-title">
                    Проверьте выбранные книги перед оформлением заказа
                </h1>
                <div class="cart-layout">
                    <section aria-label="Товары в корзине" class="cart-items" data-cart-items="true" id="cart-items">
                    </section>
                    <aside class="cart-total">
                        <h2 class="cart-total__title">Итого по заказу</h2>
                        <div class="cart-total__rows">
                            <div class="cart-total__row">
                                <span>Товары</span>
                                <span id="cart-subtotal">0 ₽</span>
                            </div>
                            <div class="cart-total__row">
                                <span>Скидка 10%</span>
                                <span id="cart-discount">0 ₽</span>
                            </div>
                        </div>
                        <div class="cart-total__result">
                            <span>Итого</span>
                            <span id="cart-total">0 ₽</span>
                        </div>
                        <a class="btn btn--accent cart-total__button" href="checkout.php" id="checkout-btn">
                            Перейти к оформлению
                        </a>
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