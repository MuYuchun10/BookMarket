<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Детальная страница книги 1984 в интернет-магазине BookMarket." name="description" />
    <title>1984 - BookMarket</title>
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
        <section aria-labelledby="book-title" class="book-page">
            <div class="container">
                <nav aria-label="Хлебные крошки" class="breadcrumbs">
                    <a class="breadcrumbs__link" href="index.php">Главная</a>
                    <span class="breadcrumbs__separator">/</span>
                    <a class="breadcrumbs__link" href="catalog.php">Каталог</a>
                    <span class="breadcrumbs__separator">/</span>
                    <a class="breadcrumbs__link" href="catalog.php?category=fiction">Художественная литература</a>
                    <span class="breadcrumbs__separator">/</span>
                    <span class="breadcrumbs__current">1984</span>
                </nav>
                <div class="book-layout">
                    <div class="book-gallery">
                        <div class="book-gallery__frame">
                            <img alt="Обложка книги 1984" class="book-gallery__image" data-fallback-title="1984"
                                id="mainImage" src="" />
                        </div>
                        <div aria-label="Просмотр книги" class="book-gallery__thumbs">
                            <button class="product__thumb active" data-image="images/books/1984-front.jpg"
                                data-label="Обложка спереди" data-tone="#d9c3aa" data-view="front"
                                type="button">Спереди</button>
                            <button class="product__thumb" data-image="images/books/1984-back.jpg"
                                data-label="Задняя сторона обложки" data-tone="#cfbea6" data-view="back"
                                type="button">Сзади</button>
                            <button class="product__thumb" data-image="images/books/1984-spread.jpg"
                                data-label="Разворот книги" data-tone="#d8c9b7" data-view="spread"
                                type="button">Разворот</button>
                        </div>
                    </div>
                    <article class="book-card" data-product-author="Джордж Оруэлл" data-product-available="false"
                        data-product-category="Художественная литература" data-product-category-slug="fiction"
                        data-product-description="Культовый роман-антиутопия о тотальном контроле, свободе и цене человеческой личности."
                        data-product-id="book-1984" data-product-image="images/books/1984-front.jpg"
                        data-product-link="book.php" data-product-price="590" data-product-title="1984">
                        <div class="book-card__badge">Художественная литература</div>
                        <h1 class="book-card__title" id="book-title">1984</h1>
                        <p class="book-card__author">Джордж Оруэлл</p>
                        <div class="book-card__purchase">
                            <p class="book-card__price">590 ₽</p>
                            <p class="book-card__stock book-card__stock--out">Нет в наличии · ожидается новое
                                поступление</p>
                        </div>
                        <div class="book-card__description">
                            <p>
                                «1984» Джорджа Оруэлла — культовый роман-антиутопия о мире тотального контроля,
                                где за каждым человеком следит государство, а правда подменяется выгодной версией
                                событий.
                                Это глубокая, напряжённая и по-настоящему сильная книга о свободе, страхе, манипуляции
                                сознанием и цене человеческой личности.
                            </p>
                            <p>
                                Подойдёт тем, кто любит серьёзную классическую литературу, социальную фантастику
                                и произведения, заставляющие задуматься.
                            </p>
                        </div>
                        <div class="book-card__actions">
                            <button class="btn btn--primary add-to-cart" type="button">Добавить в корзину</button>
                            <button class="btn btn--secondary" type="button">Купить сейчас</button>
                        </div>
                        <div class="book-specs">
                            <div class="book-spec">
                                <p class="book-spec__label">Издательство</p>
                                <p class="book-spec__value">Book House</p>
                            </div>
                            <div class="book-spec">
                                <p class="book-spec__label">Год издания</p>
                                <p class="book-spec__value">2023</p>
                            </div>
                            <div class="book-spec">
                                <p class="book-spec__label">Количество страниц</p>
                                <p class="book-spec__value">352</p>
                            </div>
                            <div class="book-spec">
                                <p class="book-spec__label">Язык</p>
                                <p class="book-spec__value">Русский</p>
                            </div>
                            <div class="book-spec">
                                <p class="book-spec__label">Тип обложки</p>
                                <p class="book-spec__value">Твёрдая</p>
                            </div>
                            <div class="book-spec">
                                <p class="book-spec__label">Артикул</p>
                                <p class="book-spec__value">BK-1984-14</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="js/books-data.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
