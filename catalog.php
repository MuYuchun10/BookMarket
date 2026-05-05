<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Каталог книг интернет-магазина BookMarket." name="description" />
    <title>Каталог - BookMarket</title>
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
        <section aria-labelledby="catalog-title" class="catalog-page">
            <div class="container">
                <p class="catalog-page__eyebrow">Каталог</p>
                <h1 class="catalog-page__title" id="catalog-title">
                    Выберите книги по жанру, автору и цене
                </h1>
                <div class="catalog-layout">
                    <aside class="catalog-sidebar">
                        <div class="catalog-panel">
                            <h2 class="catalog-panel__title">Фильтры</h2>
                            <form action="catalog.php" class="catalog-filter-form" method="get">
                                <div class="catalog-filter__group">
                                    <label class="catalog-filter__label" for="catalog-search">Название книги</label>
                                    <input class="catalog-filter__input" id="catalog-search" name="query"
                                        placeholder="Введите название книги" type="text" />
                                </div>
                                <div class="catalog-filter__group">
                                    <label class="catalog-filter__label" for="catalog-category">Категория</label>
                                    <select class="catalog-filter__select" id="catalog-category" name="category">
                                        <option value="">Категория</option>
                                        <option value="fiction">Художественная литература</option>
                                        <option value="fantasy">Фантастика</option>
                                        <option value="detective">Детективы</option>
                                        <option value="study">Учебная литература</option>
                                        <option value="business">Бизнес</option>
                                        <option value="self-development">Саморазвитие</option>
                                    </select>
                                </div>
                                <div class="catalog-filter__group">
                                    <label class="catalog-filter__label" for="catalog-author">Автор</label>
                                    <input class="catalog-filter__input" id="catalog-author" name="author"
                                        placeholder="Введите автора" type="text" />
                                </div>
                                <div class="catalog-filter__group">
                                    <span class="catalog-filter__label">Цена</span>
                                    <div class="catalog-filter__price">
                                        <input class="catalog-filter__input" min="0" name="price-from"
                                            placeholder="от 300" type="number" />
                                        <input class="catalog-filter__input" min="0" name="price-to"
                                            placeholder="до 1500" type="number" />
                                    </div>
                                </div>

                                <div class="catalog-filter__actions"><button
                                        class="btn btn--accent catalog-filter__button" type="submit">
                                        Применить фильтры
                                    </button><a class="catalog-filter__clear" href="catalog.php">Сбросить</a></div>
                            </form>
                        </div>
                    </aside>
                    <section class="catalog-content">
                        <div class="catalog-toolbar">
                            <div class="catalog-toolbar__info">
                                <h2 class="catalog-toolbar__title">Найдено 0 книг</h2>
                                <p class="catalog-toolbar__text">Фильтры можно комбинировать</p>
                            </div>
                            <div aria-label="Сортировка" class="catalog-toolbar__actions">
                                <button aria-pressed="true" class="sort-btn sort-btn--active" data-sort="price-asc"
                                    type="button">Сначала дешевле</button>
                                <button aria-pressed="false" class="sort-btn" data-sort="price-desc"
                                    type="button">Сначала дороже</button>
                            </div>
                        </div>
                        <div class="products-grid catalog-products" id="catalog-products">
                            <article class="product-card" data-product-author="Джордж Оруэлл"
                                data-product-available="false" data-product-category="Художественная литература"
                                data-product-category-slug="fiction"
                                data-product-description="Культовый роман-антиутопия о тотальном контроле, свободе и цене человеческой личности."
                                data-product-id="book-1984" data-product-image="images/books/1984-front.jpg"
                                data-product-link="book.php" data-product-price="590" data-product-title="1984">
                                <a aria-label="Открыть страницу книги 1984" class="product-card__image"
                                    href="book.php">
                                    Обложка книги
                                </a>
                                <div class="product-card__content">
                                    <h3 class="product-card__title">
                                        <a href="book.php">1984</a>
                                    </h3>
                                    <p class="product-card__author">Джордж Оруэлл</p>
                                    <div class="product-card__meta">
                                        <p class="product-card__price">590 ₽</p>
                                        <p class="product-card__status product-card__status--out">Нет в наличии</p>
                                    </div>
                                    <div class="product-card__actions"><button class="product-card__btn add-to-cart"
                                            title="Проверить наличие" type="button">Добавить в корзину</button><button
                                            class="product-card__quick-btn open-modal" type="button">Быстрый
                                            просмотр</button></div>
                                </div>
                            </article>
                            <article class="product-card" data-product-author="М. Булгаков"
                                data-product-category="Классика" data-product-category-slug="fiction"
                                data-product-description="Мистический роман о добре и зле, любви, сатире и свободе выбора."
                                data-product-id="book-master-margarita"
                                data-product-image="images/books/master-margarita-front.jpg"
                                data-product-link="catalog.php" data-product-price="720"
                                data-product-title="Мастер и Маргарита">
                                <div aria-label="Обложка книги Мастер и Маргарита" class="product-card__image">Обложка
                                    книги</div>
                                <div class="product-card__content">
                                    <h3 class="product-card__title">Мастер и Маргарита</h3>
                                    <p class="product-card__author">М. Булгаков</p>
                                    <div class="product-card__meta">
                                        <p class="product-card__price">720 ₽</p>
                                        <p class="product-card__status">В наличии</p>
                                    </div>
                                    <div class="product-card__actions"><button class="product-card__btn add-to-cart"
                                            type="button">Добавить в корзину</button><button
                                            class="product-card__quick-btn open-modal" type="button">Быстрый
                                            просмотр</button></div>
                                </div>
                            </article>
                            <article class="product-card" data-product-author="Джеймс Клир"
                                data-product-category="Саморазвитие" data-product-category-slug="self-development"
                                data-product-description="Практическая книга о том, как маленькие привычки приводят к большим результатам."
                                data-product-id="book-atomic-habits"
                                data-product-image="images/books/atomic-habits-front.jpg"
                                data-product-link="catalog.php" data-product-price="850"
                                data-product-title="Атомные привычки">
                                <div aria-label="Обложка книги Атомные привычки" class="product-card__image">Обложка
                                    книги</div>
                                <div class="product-card__content">
                                    <h3 class="product-card__title">Атомные привычки</h3>
                                    <p class="product-card__author">Джеймс Клир</p>
                                    <div class="product-card__meta">
                                        <p class="product-card__price">850 ₽</p>
                                        <p class="product-card__status">В наличии</p>
                                    </div>
                                    <div class="product-card__actions"><button class="product-card__btn add-to-cart"
                                            type="button">Добавить в корзину</button><button
                                            class="product-card__quick-btn open-modal" type="button">Быстрый
                                            просмотр</button></div>
                                </div>
                            </article>
                            <article class="product-card" data-product-author="Дж. К. Роулинг"
                                data-product-category="Фэнтези" data-product-category-slug="fantasy"
                                data-product-description="Известная история о школе магии, дружбе, взрослении и борьбе со злом."
                                data-product-id="book-harry-potter"
                                data-product-image="images/books/harry-potter-front.jpg"
                                data-product-link="catalog.php" data-product-price="910"
                                data-product-title="Гарри Поттер">
                                <div aria-label="Обложка книги Гарри Поттер и философский камень"
                                    class="product-card__image">Обложка книги</div>
                                <div class="product-card__content">
                                    <h3 class="product-card__title">Гарри Поттер</h3>
                                    <p class="product-card__author">Дж. К. Роулинг</p>
                                    <div class="product-card__meta">
                                        <p class="product-card__price">910 ₽</p>
                                        <p class="product-card__status">В наличии</p>
                                    </div>
                                    <div class="product-card__actions"><button class="product-card__btn add-to-cart"
                                            type="button">Добавить в корзину</button><button
                                            class="product-card__quick-btn open-modal" type="button">Быстрый
                                            просмотр</button></div>
                                </div>
                            </article>
                            <article class="product-card" data-product-author="Роберт Мартин"
                                data-product-category="Программирование" data-product-category-slug="study"
                                data-product-description="Книга о принципах написания понятного, поддерживаемого и качественного кода."
                                data-product-id="book-clean-code" data-product-image="images/books/clean-code-front.jpg"
                                data-product-link="catalog.php" data-product-price="1200"
                                data-product-title="Чистый код">
                                <div aria-label="Обложка книги Чистый код" class="product-card__image">Обложка книги
                                </div>
                                <div class="product-card__content">
                                    <h3 class="product-card__title">Чистый код</h3>
                                    <p class="product-card__author">Роберт Мартин</p>
                                    <div class="product-card__meta">
                                        <p class="product-card__price">1200 ₽</p>
                                        <p class="product-card__status">В наличии</p>
                                    </div>
                                    <div class="product-card__actions"><button class="product-card__btn add-to-cart"
                                            type="button">Добавить в корзину</button><button
                                            class="product-card__quick-btn open-modal" type="button">Быстрый
                                            просмотр</button></div>
                                </div>
                            </article>
                            <article class="product-card" data-product-author="Агата Кристи"
                                data-product-category="Детектив" data-product-category-slug="detective"
                                data-product-description="Классический детектив с Эркюлем Пуаро и напряжённой атмосферой расследования."
                                data-product-id="book-orient-express"
                                data-product-image="images/books/orient-express-front.jpg"
                                data-product-link="catalog.php" data-product-price="640"
                                data-product-title="Убийство в Восточном экспрессе">
                                <div aria-label="Обложка книги Убийство в Восточном экспрессе"
                                    class="product-card__image">Обложка книги</div>
                                <div class="product-card__content">
                                    <h3 class="product-card__title">Убийство в Восточном экспрессе</h3>
                                    <p class="product-card__author">Агата Кристи</p>
                                    <div class="product-card__meta">
                                        <p class="product-card__price">640 ₽</p>
                                        <p class="product-card__status">В наличии</p>
                                    </div>
                                    <div class="product-card__actions"><button class="product-card__btn add-to-cart"
                                            type="button">Добавить в корзину</button><button
                                            class="product-card__quick-btn open-modal" type="button">Быстрый
                                            просмотр</button></div>
                                </div>
                            </article>
                        </div>
                    </section>
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
