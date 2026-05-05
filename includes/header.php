<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLogged = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
?>

<header class="header">
    <div class="container">
        <div class="header__container">
            <a aria-label="BookMarket — перейти на главную" class="logo" href="index.php">
                <span class="logo__icon">B</span>
                <span class="logo__content">
                    <span class="logo__text">BookMarket</span>
                    <span class="logo__subtitle">Интернет-магазин книг</span>
                </span>
            </a>
            <nav aria-label="Основная навигация" class="nav">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a class="nav__link nav__link--active" href="index.php">Главная</a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="catalog.php">Каталог</a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="contacts.php">Контакты</a>
                    </li>
                </ul>
            </nav>
            <div class="user-nav">
                <?php if ($isLogged): ?>
                    <a class="user-nav__link" href="profile.php">
                        <?php echo htmlspecialchars($userName); ?>
                    </a>
                    <a class="user-nav__link" href="handlers/logout.php">Выйти</a>
                <?php else: ?>
                    <a class="user-nav__link" href="account.php">Войти</a>
                <?php endif; ?>

                <a class="user-nav__link user-nav__link--accent" href="cart.php">
                    Корзина (<span class="cart-count">0</span>)
                </a>
            </div>
        </div>
    </div>
</header>