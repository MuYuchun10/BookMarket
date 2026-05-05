<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Пользователь';
$userEmail = $_SESSION['user_email'] ?? '';
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
    <header class="header">
        <div class="container">
            <div class="header__container">
                <a aria-label="BookMarket — перейти на главную" class="logo" href="index.html">
                    <span class="logo__icon">B</span>
                    <span class="logo__content">
                        <span class="logo__text">BookMarket</span>
                        <span class="logo__subtitle">Интернет-магазин книг</span>
                    </span>
                </a>
                <nav aria-label="Основная навигация" class="nav">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a class="nav__link" href="index.html">Главная</a>
                        </li>
                        <li class="nav__item">
                            <a class="nav__link" href="catalog.html">Каталог</a>
                        </li>
                        <li class="nav__item">
                            <a class="nav__link" href="contacts.php">Контакты</a>
                        </li>
                    </ul>
                </nav>
                <div class="user-nav">
                    <a class="user-nav__link" href="profile.php">Личный кабинет</a>
                    <a class="user-nav__link user-nav__link--accent" href="cart.html">
                        Корзина (<span class="cart-count">0</span>)
                    </a>
                </div>
            </div>
        </div>
    </header>
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
                    <form action="#" class="profile-form" id="profile-form" method="post">
                        <label class="visually-hidden" for="profile-name">Имя</label>
                        <input autocomplete="name" class="profile-form__input" id="profile-name" name="name"
                            placeholder="Имя" type="text" value="<?php echo htmlspecialchars($userName); ?>" />

                        <label class="visually-hidden" for="profile-email">Email</label>
                        <input autocomplete="email" class="profile-form__input" id="profile-email" name="email"
                            placeholder="Email" type="email" 
                            value="<?php echo htmlspecialchars($userEmail); ?>" />

                        <label class="visually-hidden" for="profile-phone">Телефон</label>
                        <input autocomplete="tel" class="profile-form__input" id="profile-phone" name="phone"
                            placeholder="Телефон" type="tel" />
                        <label class="visually-hidden" for="profile-city">Город</label>
                        <input autocomplete="address-level2" class="profile-form__input" id="profile-city" name="city"
                            placeholder="Город" type="text" />
                        <label class="visually-hidden" for="profile-address">Адрес</label>
                        <input autocomplete="street-address" class="profile-form__input" id="profile-address"
                            name="address" placeholder="Адрес" type="text" />
                        <label class="visually-hidden" for="profile-postcode">Почтовый индекс</label>
                        <input autocomplete="postal-code" class="profile-form__input" id="profile-postcode"
                            name="postcode" placeholder="Почтовый индекс" type="text" />
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
                    <div class="orders-list">
                        <article class="order-row">
                            <div class="order-row__id">#10021</div>
                            <div class="order-row__count">2 книги</div>
                            <div class="order-row__price">2 540 ₽</div>
                            <div class="order-row__status">Доставлен</div>
                        </article>
                        <article class="order-row">
                            <div class="order-row__id">#10018</div>
                            <div class="order-row__count">1 книга</div>
                            <div class="order-row__price">720 ₽</div>
                            <div class="order-row__status">В обработке</div>
                        </article>
                        <article class="order-row">
                            <div class="order-row__id">#10012</div>
                            <div class="order-row__count">3 книги</div>
                            <div class="order-row__price">3 180 ₽</div>
                            <div class="order-row__status">Отправлен</div>
                        </article>
                    </div>
                </section>
            </div>
        </section>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer__container">
                <div class="footer__column">
                    <h3 class="footer__title">BookMarket</h3>
                    <p class="footer__text">
                        Онлайн-магазин печатных книг для учёбы, отдыха и саморазвития.
                    </p>
                </div>
                <div class="footer__column">
                    <h3 class="footer__title">Разделы</h3>
                    <ul class="footer__list">
                        <li class="footer__item">
                            <a class="footer__link" href="index.html">Главная</a>
                        </li>
                        <li class="footer__item">
                            <a class="footer__link" href="catalog.html">Каталог</a>
                        </li>
                        <li class="footer__item">
                            <a class="footer__link" href="contacts.php">Контакты</a>
                        </li>
                    </ul>
                </div>
                <div class="footer__column">
                    <h3 class="footer__title">Покупателю</h3>
                    <ul class="footer__list">
                        <li class="footer__item">
                            <a class="footer__link" href="profile.php">Личный кабинет</a>
                        </li>
                        <li class="footer__item">
                            <a class="footer__link" href="cart.html">Корзина</a>
                        </li>
                        <li class="footer__item">
                            <a class="footer__link js-checkout-link" href="checkout.html">Оформление заказа</a>
                        </li>
                    </ul>
                </div>
                <div class="footer__column">
                    <h3 class="footer__title">Контакты</h3>
                    <address class="footer__address">
                        <a class="footer__link" href="tel:+79001234567">+7 (900) 123-45-67</a><br />
                        <a class="footer__link" href="mailto:bookmarket@mail.ru">bookmarket@mail.ru</a><br />
                        г. Орёл
                    </address>
                </div>
            </div>
        </div>
    </footer>
    <script src="js/books-data.js"></script>
    <script src="js/main.js"></script>
</body>

</html>