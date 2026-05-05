<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: account.php?redirect=checkout.php&notice=Чтобы оформить заказ, сначала войдите или зарегистрируйтесь');
    exit;
}
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
                        <form action="#" class="checkout-form" id="checkout-form" method="post" novalidate="novalidate">
                            <div aria-live="polite" class="checkout-form__success"></div>
                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-name">Имя получателя</label>
                                <input autocomplete="name" class="checkout-form__input" id="checkout-name" name="name"
                                    placeholder="Имя получателя" required="" type="text" />
                            </div>
                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-phone">Номер телефона</label>
                                <input autocomplete="tel" class="checkout-form__input" id="checkout-phone" name="phone"
                                    placeholder="Номер телефона" required="" type="tel" />
                            </div>
                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-city">Город</label>
                                <input autocomplete="address-level2" class="checkout-form__input" id="checkout-city"
                                    name="city" placeholder="Город" required="" type="text" />
                            </div>
                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-street">Улица</label>
                                <input autocomplete="street-address" class="checkout-form__input" id="checkout-street"
                                    name="street" placeholder="Улица" required="" type="text" />
                            </div>
                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-house">Дом</label>
                                <input class="checkout-form__input" id="checkout-house" name="house" placeholder="Дом"
                                    required="" type="text" />
                            </div>
                            <div class="checkout-form__group">
                                <label class="visually-hidden" for="checkout-flat">Квартира</label>
                                <input class="checkout-form__input" id="checkout-flat" name="flat"
                                    placeholder="Квартира" type="text" />
                            </div>
                            <div class="checkout-form__group checkout-form__group--full">
                                <label class="visually-hidden" for="checkout-comment">Комментарий к заказу</label>
                                <textarea class="checkout-form__textarea" id="checkout-comment" name="comment"
                                    placeholder="Комментарий к заказу"></textarea>
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
