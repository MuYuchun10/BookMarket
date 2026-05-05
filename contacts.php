<?php
session_start();

$errors = $_SESSION['contact_errors'] ?? [];
$old = $_SESSION['contact_old'] ?? [];
$success = $_SESSION['contact_success'] ?? '';

unset($_SESSION['contact_errors'], $_SESSION['contact_old'], $_SESSION['contact_success']);
?>
<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Контакты интернет-магазина BookMarket." name="description" />
    <title>Контакты - BookMarket</title>
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
        <section aria-labelledby="contacts-title" class="contacts-page">
            <div class="container">
                <p class="contacts-page__eyebrow">Контакты</p>
                <h1 class="contacts-page__title" id="contacts-title">
                    Свяжитесь с нами удобным способом
                </h1>
                <div class="contacts-layout">
                    <div class="contacts-info">
                        <article class="contact-card">
                            <div class="contact-card__row">
                                <span aria-hidden="true" class="contact-card__icon">☎</span>
                                <div class="contact-card__content">
                                    <p class="contact-card__label">Телефон</p>
                                    <a class="contact-card__value" href="tel:+79001234567">+7 (900) 123-45-67</a>
                                </div>
                            </div>
                        </article>
                        <article class="contact-card">
                            <div class="contact-card__row">
                                <span aria-hidden="true" class="contact-card__icon">✉</span>
                                <div class="contact-card__content">
                                    <p class="contact-card__label">Email</p>
                                    <a class="contact-card__value"
                                        href="mailto:bookmarket@mail.ru">bookmarket@mail.ru</a>
                                </div>
                            </div>
                        </article>
                        <article class="contact-card">
                            <div class="contact-card__row">
                                <span aria-hidden="true" class="contact-card__icon">⌖</span>
                                <div class="contact-card__content">
                                    <p class="contact-card__label">Адрес</p>
                                    <p class="contact-card__value">г. Орёл, ул. Примерная, 10</p>
                                </div>
                            </div>
                        </article>
                        <article class="contact-card contact-card--schedule">
                            <h2 class="contact-card__title">График работы</h2>
                            <div class="contact-schedule">
                                <p class="contact-schedule__item">Пн–Пт: 09:00–20:00</p>
                                <p class="contact-schedule__item">Сб: 10:00–18:00</p>
                                <p class="contact-schedule__item">Вс: выходной</p>
                            </div>
                        </article>
                    </div>
                    <section aria-labelledby="feedback-title" class="contact-form-card">
                        <h2 class="contact-form-card__title" id="feedback-title">Форма обратной связи</h2>
                        <form action="handlers/contact_handler.php" class="contact-form" method="post"
                            novalidate="novalidate">
                            <?php if (!empty($success)): ?>
                                <div aria-live="polite" class="contact-form__success">
                                    <?php echo htmlspecialchars($success); ?>
                                </div>
                            <?php endif; ?>
                            <div class="contact-form__group">
                                <label class="visually-hidden" for="contact-name">Ваше имя</label>
                                <input autocomplete="name" class="contact-form__input" id="contact-name" name="name"
                                    placeholder="Ваше имя" required="" type="text"
                                    value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" />

                                <?php if (isset($errors['name'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($errors['name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="contact-form__group">
                                <label class="visually-hidden" for="contact-email">Email</label>
                                <input autocomplete="email" class="contact-form__input" id="contact-email" name="email"
                                    placeholder="Email" required="" type="email"
                                    value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" />

                                <?php if (isset($errors['email'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($errors['email']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="contact-form__group">
                                <label class="visually-hidden" for="contact-subject">Тема сообщения</label>
                                <input class="contact-form__input" id="contact-subject" name="subject"
                                    placeholder="Тема сообщения" required="" type="text"
                                    value="<?php echo htmlspecialchars($old['subject'] ?? ''); ?>" />

                                <?php if (isset($errors['subject'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($errors['subject']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="contact-form__group">
                                <label class="visually-hidden" for="contact-message">Сообщение</label>
                                <textarea class="contact-form__textarea" id="contact-message" name="message"
                                    placeholder="Сообщение"
                                    required=""><?php echo htmlspecialchars($old['message'] ?? ''); ?></textarea>

                                <?php if (isset($errors['message'])): ?>
                                    <div class="error-message">
                                        <?php echo htmlspecialchars($errors['message']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn--accent contact-form__button" type="submit">
                                Отправить сообщение
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