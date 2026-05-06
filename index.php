<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="BookMarket — интернет-магазин печатных книг для учёбы, отдыха и саморазвития." name="description" />
    <title>Главная - BookMarket</title>
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
        <section class="hero">
            <div class="container hero__container">
                <div class="hero__content">
                    <p class="hero__subtitle">Новинки и бестселлеры</p>
                    <h1 class="hero__title">Найди свою следующую любимую книгу</h1>
                    <p class="hero__description">
                        Художественная литература, учебные издания, книги по бизнесу
                        и саморазвитию — всё в одном удобном каталоге.
                    </p>
                    <div class="hero__actions">
                        <a class="btn btn--primary" href="catalog.php">Перейти в каталог</a>
                        <a class="btn btn--secondary" href="#featured">Популярные книги</a>
                    </div>
                </div>
                <aside class="hero__aside">
                    <h2 class="hero__aside-title">Быстрый поиск</h2>
                    <form action="catalog.php" class="search-form" method="get">
                        <label class="visually-hidden" for="search-query">Название книги</label>
                        <input class="search-form__input" id="search-query" name="query"
                            placeholder="Название книги" type="text" />
                        <label class="visually-hidden" for="search-author">Автор</label>
                        <input class="search-form__input" id="search-author" name="author"
                            placeholder="Автор" type="text" />
                        <label class="visually-hidden" for="search-category">Категория</label>
                        <select class="search-form__select" id="search-category" name="category">
                            <option value="">Категория</option>
                            <option value="fiction">Художественная</option>
                            <option value="fantasy">Фантастика</option>
                            <option value="detective">Детективы</option>
                            <option value="study">Учебная</option>
                            <option value="business">Бизнес</option>
                            <option value="self-development">Саморазвитие</option>
                        </select>
                        <div class="search-form__price">
                            <label class="visually-hidden" for="search-price-from">Цена от</label>
                            <input class="search-form__input" id="search-price-from" min="0" name="price-from"
                                placeholder="Цена от" type="number" />
                            <label class="visually-hidden" for="search-price-to">Цена до</label>
                            <input class="search-form__input" id="search-price-to" min="0" name="price-to"
                                placeholder="Цена до" type="number" />
                        </div>
                        <button class="btn btn--accent search-form__button" type="submit">
                            Найти книгу
                        </button>
                    </form>
                </aside>
            </div>
        </section>
        <section aria-labelledby="categories-title" class="categories">
            <div class="container">
                <div class="categories__block">
                    <h2 class="section-title" id="categories-title">Популярные категории</h2>
                    <ul class="categories__list">
                        <li class="categories__item">
                            <a class="category-pill" href="catalog.php?category=fiction">Художественная</a>
                        </li>
                        <li class="categories__item">
                            <a class="category-pill" href="catalog.php?category=fantasy">Фантастика</a>
                        </li>
                        <li class="categories__item">
                            <a class="category-pill" href="catalog.php?category=detective">Детективы</a>
                        </li>
                        <li class="categories__item">
                            <a class="category-pill" href="catalog.php?category=study">Учебная</a>
                        </li>
                        <li class="categories__item">
                            <a class="category-pill" href="catalog.php?category=business">Бизнес</a>
                        </li>
                        <li class="categories__item">
                            <a class="category-pill" href="catalog.php?category=self-development">Саморазвитие</a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section aria-labelledby="featured-title" class="featured" id="featured">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title" id="featured-title">Популярные книги</h2>
                    <a class="section-link" href="catalog.php">Смотреть все</a>
                </div>
                <div class="products-grid" id="featured-products" data-products-scope="featured" data-products-limit="4"></div>
            </div>
        </section>
        <section aria-labelledby="advantages-title" class="advantages">
            <div class="container">
                <h2 class="visually-hidden" id="advantages-title">Преимущества магазина</h2>
                <div class="advantages__grid">
                    <article class="advantage-card">
                        <span aria-hidden="true" class="advantage-card__icon advantage-card__icon--delivery"></span>
                        <h3 class="advantage-card__title">Быстрая доставка</h3>
                        <p class="advantage-card__text">
                            Оформление заказа за пару шагов и понятная форма адреса.
                        </p>
                    </article>
                    <article class="advantage-card">
                        <span aria-hidden="true" class="advantage-card__icon advantage-card__icon--stock"></span>
                        <h3 class="advantage-card__title">Актуальное наличие</h3>
                        <p class="advantage-card__text">
                            На карточке книги сразу видно, доступен ли товар на складе.
                        </p>
                    </article>
                    <article class="advantage-card">
                        <span aria-hidden="true" class="advantage-card__icon advantage-card__icon--account"></span>
                        <h3 class="advantage-card__title">Удобный кабинет</h3>
                        <p class="advantage-card__text">
                            Пользователь может посмотреть историю заказов и изменить профиль.
                        </p>
                    </article>
                </div>
            </div>
        </section>
        <section aria-labelledby="promo-title" class="promo">
            <div class="container">
                <div class="promo__container">
                    <h2 class="promo__title" id="promo-title">Скидка 10% на первый заказ</h2>
                    <a class="btn btn--light" href="catalog.php">Выбрать книги</a>
                </div>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <div aria-hidden="true" class="modal" id="quickViewModal">
        <div aria-labelledby="quickViewTitle" aria-modal="true" class="modal__content" role="dialog">
            <button aria-label="Закрыть окно" class="modal__close" type="button">×</button>
            <h2 class="modal__title" id="quickViewTitle">Быстрый просмотр</h2>
            <div class="modal__body"></div>
        </div>
    </div>
    <script src="js/books-data.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
