<?php
session_start();

$loginErrors = $_SESSION['login_errors'] ?? [];
$loginOld = $_SESSION['login_old'] ?? [];

$registerErrors = $_SESSION['register_errors'] ?? [];
$registerOld = $_SESSION['register_old'] ?? [];

unset(
    $_SESSION['login_errors'],
    $_SESSION['login_old'],
    $_SESSION['register_errors'],
    $_SESSION['register_old']
);

$redirectTarget = $_GET['redirect'] ?? 'profile.php';
$allowedRedirects = ['profile.php', 'checkout.php', 'cart.php'];

if (!in_array($redirectTarget, $allowedRedirects, true)) {
    $redirectTarget = 'profile.php';
}
?>
<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Вход и регистрация в интернет-магазине BookMarket." name="description" />
    <title>Вход / Регистрация - BookMarket</title>
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
    <main class="main auth-main">
        <section class="auth-page">
            <div class="container">
                
                <div class="auth-page__grid">
                    <section aria-labelledby="login-title" class="auth-card">
                        <h1 class="auth-card__title" id="login-title">Вход</h1>
                        <p class="auth-card__subtitle">
                            Введите email и пароль, чтобы войти в личный кабинет
                        </p>
                        <form action="handlers/login_handler.php" class="auth-form" method="post" novalidate>
                            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectTarget); ?>">
                            <label class="visually-hidden" for="login-email">Email</label>
                            <input autocomplete="email" class="auth-form__input" id="login-email" name="email"
                                placeholder="Email" required="" type="email"
                                value="<?php echo htmlspecialchars($loginOld['email'] ?? ''); ?>" />

                            <?php if (isset($loginErrors['email'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($loginErrors['email']); ?>
                                </div>
                            <?php endif; ?>

                            <label class="visually-hidden" for="login-password">Пароль</label>
                            <input autocomplete="current-password" class="auth-form__input" id="login-password"
                                name="password" placeholder="Пароль" required="" type="password" />

                            <?php if (isset($loginErrors['password'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($loginErrors['password']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($loginErrors['login'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($loginErrors['login']); ?>
                                </div>
                            <?php endif; ?>

                            <button class="auth-form__button auth-form__button--dark" type="submit">
                                Войти
                            </button>
                        </form>
                    </section>
                    <section aria-labelledby="register-title" class="auth-card">
                        <h2 class="auth-card__title" id="register-title">Регистрация</h2>
                        <p class="auth-card__subtitle">
                            Создайте аккаунт, чтобы сохранять данные и отслеживать заказы
                        </p>
                        <form action="handlers/register_handler.php" class="auth-form" method="post" novalidate>
                            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectTarget); ?>">
                            <label class="visually-hidden" for="register-name">Имя</label>
                            <input autocomplete="name" class="auth-form__input" id="register-name" name="name"
                                placeholder="Имя" required="" type="text"
                                value="<?php echo htmlspecialchars($registerOld['name'] ?? ''); ?>" />

                            <?php if (isset($registerErrors['name'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($registerErrors['name']); ?>
                                </div>
                            <?php endif; ?>

                            <label class="visually-hidden" for="register-email">Email</label>
                            <input autocomplete="email" class="auth-form__input" id="register-email" name="email"
                                placeholder="Email" required="" type="email"
                                value="<?php echo htmlspecialchars($registerOld['email'] ?? ''); ?>" />

                            <?php if (isset($registerErrors['email'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($registerErrors['email']); ?>
                                </div>
                            <?php endif; ?>

                            <label class="visually-hidden" for="register-password">Пароль</label>
                            <input autocomplete="new-password" class="auth-form__input" id="register-password"
                                name="password" placeholder="Пароль" required="" type="password" />

                            <?php if (isset($registerErrors['password'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($registerErrors['password']); ?>
                                </div>
                            <?php endif; ?>

                            <label class="visually-hidden" for="register-password-repeat">Повторите пароль</label>
                            <input autocomplete="new-password" class="auth-form__input" id="register-password-repeat"
                                name="confirm_password" placeholder="Повторите пароль" required="" type="password" />

                            <?php if (isset($registerErrors['confirm'])): ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($registerErrors['confirm']); ?>
                                </div>
                            <?php endif; ?>
                            <button class="auth-form__button auth-form__button--accent" type="submit">
                                Создать аккаунт
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="js/books-data.js"></script>
    <script src="js/main.js"></script>
</body>

</html>