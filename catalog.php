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
                        <div class="products-grid catalog-products" id="catalog-products" data-products-scope="all"></div>
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
